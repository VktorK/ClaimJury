<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use app\models\Purchase;

/**
 * PurchaseController handles purchase management
 */
class PurchaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all purchases for current user
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Purchase::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['purchase_date' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $totalAmount = Purchase::getTotalAmountForUser(Yii::$app->user->id);
        $purchasesCount = Purchase::getPurchasesCountForUser(Yii::$app->user->id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'totalAmount' => $totalAmount,
            'purchasesCount' => $purchasesCount,
        ]);
    }

    /**
     * Displays a single purchase
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new purchase
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Purchase();
        $model->user_id = Yii::$app->user->id;
        $model->currency = 'RUB';

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            // Обработка загрузки чека
            $receiptFile = UploadedFile::getInstance($model, 'receipt_image');
            if ($receiptFile) {
                $model->uploadReceipt($receiptFile);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Покупка успешно добавлена.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при добавлении покупки.');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing purchase
     *
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            // Обработка загрузки чека
            $receiptFile = UploadedFile::getInstance($model, 'receipt_image');
            if ($receiptFile) {
                $model->uploadReceipt($receiptFile);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Покупка успешно обновлена.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при обновлении покупки.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing purchase
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Удаляем файл чека если есть
        if ($model->receipt_image) {
            $uploadPath = Yii::getAlias('@webroot/uploads/receipts/');
            $filePath = $uploadPath . $model->receipt_image;
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $model->delete();
        
        Yii::$app->session->setFlash('success', 'Покупка удалена.');
        return $this->redirect(['index']);
    }

    /**
     * Deletes receipt image
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteReceipt($id)
    {
        $model = $this->findModel($id);
        
        if ($model->receipt_image) {
            $uploadPath = Yii::getAlias('@webroot/uploads/receipts/');
            $filePath = $uploadPath . $model->receipt_image;
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $model->receipt_image = null;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Чек удален.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the Purchase model based on its primary key value and user ownership
     *
     * @param int $id
     * @return Purchase
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Purchase::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ]);
        
        if ($model === null) {
            throw new NotFoundHttpException('Покупка не найдена.');
        }

        return $model;
    }
}
