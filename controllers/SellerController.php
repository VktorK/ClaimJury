<?php

namespace app\controllers;

use Yii;
use app\models\Seller;
use app\models\SellerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * SellerController implements the CRUD actions for Seller model.
 */
class SellerController extends Controller
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
     * Lists all Seller models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SellerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Seller model.
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
     * Creates a new Seller model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Seller();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Продавец успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Seller model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Продавец успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Seller model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Продавец успешно удален.');

        return $this->redirect(['index']);
    }

    /**
     * AJAX создание нового продавца
     * @return array
     */
    public function actionCreateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new Seller();
        $model->load(Yii::$app->request->post());
        
        // Устанавливаем user_id для текущего пользователя
        $model->user_id = Yii::$app->user->id;
        
        // Устанавливаем purchases_id из POST данных
        $purchasesId = Yii::$app->request->post('purchases_id');
        if ($purchasesId) {
            $model->purchases_id = $purchasesId;
        }
        
        if ($model->save()) {
            return [
                'success' => true,
                'id' => $model->id,
                'title' => $model->title,
                'message' => 'Продавец успешно создан.'
            ];
        } else {
            return [
                'success' => false,
                'errors' => $model->errors,
                'message' => 'Ошибка при создании продавца.'
            ];
        }
    }

    /**
     * AJAX получение списка продавцов для пользователя
     * @return array
     */
    public function actionGetSellers()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $userId = Yii::$app->user->id;
        $sellers = Seller::getSellersForUser($userId);
        
        $result = [];
        foreach ($sellers as $seller) {
            $result[] = [
                'id' => $seller->id,
                'title' => $seller->title,
                'address' => $seller->address,
                'fullTitle' => $seller->getFullTitle()
            ];
        }
        
        return $result;
    }

    /**
     * Finds the Seller model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seller the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seller::findOne(['id' => $id, 'user_id' => Yii::$app->user->id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый продавец не найден.');
    }
}
