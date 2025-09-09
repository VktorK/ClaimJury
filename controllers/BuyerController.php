<?php

namespace app\controllers;

use Yii;
use app\models\Buyer;
use app\models\BuyerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BuyerController implements the CRUD actions for Buyer model.
 */
class BuyerController extends Controller
{
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Buyer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BuyerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Buyer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Buyer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Buyer();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // Загружаем изображение
                $imageFile = UploadedFile::getInstance($model, 'image');
                if ($imageFile) {
                    $model->uploadImage($imageFile);
                }
                
                Yii::$app->session->setFlash('success', 'Покупатель успешно создан.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Buyer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // Загружаем изображение
                $imageFile = UploadedFile::getInstance($model, 'image');
                if ($imageFile) {
                    $model->uploadImage($imageFile);
                }
                
                Yii::$app->session->setFlash('success', 'Покупатель успешно обновлен.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Buyer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Покупатель успешно удален.');
        return $this->redirect(['index']);
    }

    /**
     * Creates a new Buyer model via AJAX.
     * @return mixed
     */
    public function actionCreateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new Buyer();
        $model->load(Yii::$app->request->post());
        
        if ($model->save()) {
            // Загружаем изображение
            $imageFile = UploadedFile::getInstance($model, 'image');
            if ($imageFile) {
                $model->uploadImage($imageFile);
            }
            
            return [
                'success' => true,
                'id' => $model->id,
                'fullName' => $model->getFullName(),
                'message' => 'Покупатель успешно создан.'
            ];
        }
        
        return [
            'success' => false,
            'errors' => $model->getErrors(),
            'message' => 'Ошибка при создании покупателя.'
        ];
    }

    /**
     * Finds the Buyer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Buyer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Buyer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый покупатель не найден.');
    }
}
