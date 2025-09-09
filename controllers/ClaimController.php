<?php

namespace app\controllers;

use Yii;
use app\models\Claim;
use app\models\ClaimSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ClaimController implements the CRUD actions for Claim model.
 */
class ClaimController extends Controller
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
     * Lists all Claim models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClaimSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Фильтруем только претензии текущего пользователя
        $dataProvider->query->andWhere(['claims.user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Claim model.
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
     * Creates a new Claim model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Claim();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->id;
            $model->claim_date = time();
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Претензия успешно создана.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Claim model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Обрабатываем дату решения
            if ($model->resolution_date) {
                $model->resolution_date = strtotime($model->resolution_date);
            } else {
                $model->resolution_date = null;
            }
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Претензия успешно обновлена.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Преобразуем timestamp в дату для отображения в форме
        if ($model->resolution_date) {
            $model->resolution_date = date('Y-m-d', $model->resolution_date);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Claim model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!$model->canDelete()) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить претензию в текущем статусе.');
            return $this->redirect(['index']);
        }
        
        $model->delete();
        Yii::$app->session->setFlash('success', 'Претензия успешно удалена.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Claim model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Claim the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Claim::findOne($id)) !== null) {
            // Проверяем, что претензия принадлежит текущему пользователю
            if ($model->user_id !== Yii::$app->user->id) {
                throw new NotFoundHttpException('Претензия не найдена.');
            }
            return $model;
        }

        throw new NotFoundHttpException('Претензия не найдена.');
    }
}
