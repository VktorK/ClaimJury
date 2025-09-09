<?php

namespace app\controllers;

use Yii;
use app\models\Claim;
use app\services\PostRussiaApiService;
use app\services\MockTrackingService;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Контроллер для работы с отслеживанием документов по претензиям
 */
class TrackingController extends Controller
{
    /**
     * Использовать мок-сервис для демонстрации (true) или реальный API (false)
     */
    private $useMockService = false;

    /**
     * Получение сервиса отслеживания
     * @return PostRussiaApiService|MockTrackingService
     */
    private function getTrackingService()
    {
        if ($this->useMockService) {
            return new MockTrackingService();
        }
        return new PostRussiaApiService();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'update-tracking' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Отслеживание документов по претензии
     * @param integer $id ID претензии
     * @return mixed
     */
    public function actionIndex($id)
    {
        $claim = $this->findModel($id);
        
        return $this->render('index', [
            'claim' => $claim,
        ]);
    }

    /**
     * Обновление информации об отслеживании
     * @param integer $id ID претензии
     * @return mixed
     */
    public function actionUpdateTracking($id)
    {
        $claim = $this->findModel($id);
        
        if (!$claim->hasTrackingNumber()) {
            Yii::$app->session->setFlash('error', 'Трек-номер не указан для данной претензии.');
            return $this->redirect(['index', 'id' => $claim->id]);
        }

        $apiService = $this->getTrackingService();
        $trackingData = $apiService->trackPackage($claim->tracking_number);

        if ($trackingData === null || !isset($trackingData['success'])) {
            Yii::$app->session->setFlash('error', 'Ошибка при обращении к API Почты России.');
            return $this->redirect(['index', 'id' => $claim->id]);
        }

        if (!$trackingData['success']) {
            $message = $trackingData['message'] ?? 'Ошибка при обращении к API';
            if (isset($trackingData['error_code']) && $trackingData['error_code'] === 'PACKAGE_NOT_FOUND') {
                Yii::$app->session->setFlash('warning', 'Отправление с трек-номером ' . $claim->tracking_number . ' не найдено.');
            } else {
                Yii::$app->session->setFlash('error', $message);
            }
            return $this->redirect(['index', 'id' => $claim->id]);
        }

        if ($claim->updateTrackingInfo($trackingData)) {
            Yii::$app->session->setFlash('success', 'Информация об отслеживании обновлена.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при обновлении информации об отслеживании.');
        }

        return $this->redirect(['index', 'id' => $claim->id]);
    }

    /**
     * Добавление трек-номера к претензии
     * @param integer $id ID претензии
     * @return mixed
     */
    public function actionAddTracking($id)
    {
        $claim = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $trackingNumber = Yii::$app->request->post('tracking_number');
            
            if (empty($trackingNumber)) {
                Yii::$app->session->setFlash('error', 'Трек-номер не может быть пустым.');
                return $this->redirect(['index', 'id' => $claim->id]);
            }

            $apiService = $this->getTrackingService();
            
            if (!$apiService->validateTrackingNumber($trackingNumber)) {
                Yii::$app->session->setFlash('error', 'Неверный формат трек-номера.');
                return $this->redirect(['index', 'id' => $claim->id]);
            }

            $claim->tracking_number = $trackingNumber;
            $claim->document_sent_date = time();
            
            if ($claim->save()) {
                Yii::$app->session->setFlash('success', 'Трек-номер добавлен. Теперь можно отслеживать отправление.');
                
                // Автоматически обновляем информацию об отслеживании
                $trackingData = $apiService->trackPackage($trackingNumber);
                if ($trackingData !== null && isset($trackingData['success']) && $trackingData['success']) {
                    $claim->updateTrackingInfo($trackingData);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при сохранении трек-номера.');
            }

            return $this->redirect(['index', 'id' => $claim->id]);
        }

        return $this->render('add-tracking', [
            'claim' => $claim,
        ]);
    }

    /**
     * AJAX обновление статуса отслеживания
     * @param integer $id ID претензии
     * @return string JSON ответ
     */
    public function actionAjaxUpdate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $claim = $this->findModel($id);
        
        if (!$claim->hasTrackingNumber()) {
            return [
                'success' => false,
                'message' => 'Трек-номер не указан'
            ];
        }

        $apiService = $this->getTrackingService();
        $trackingData = $apiService->trackPackage($claim->tracking_number);

        if ($trackingData === null || !isset($trackingData['success'])) {
            return [
                'success' => false,
                'message' => 'Ошибка при обращении к API'
            ];
        }

        if (!$trackingData['success']) {
            return [
                'success' => false,
                'message' => $trackingData['message'] ?? 'Ошибка при обращении к API',
                'error_code' => $trackingData['error_code'] ?? 'UNKNOWN_ERROR'
            ];
        }

        if ($claim->updateTrackingInfo($trackingData)) {
            return [
                'success' => true,
                'message' => 'Информация обновлена',
                'data' => [
                    'status' => $claim->tracking_status,
                    'status_class' => $claim->getTrackingStatusClass(),
                    'last_update' => $claim->getFormattedLastTrackingUpdate(),
                    'delivered' => $claim->isDocumentDelivered()
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Ошибка при обновлении'
            ];
        }
    }

    /**
     * Находит модель претензии по ID
     * @param integer $id
     * @return Claim
     * @throws NotFoundHttpException если модель не найдена
     */
    protected function findModel($id)
    {
        $model = Claim::findOne($id);
        
        if ($model === null || $model->user_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Претензия не найдена.');
        }

        return $model;
    }
}
