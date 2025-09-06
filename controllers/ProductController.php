<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            // Обработка загрузки изображения
            $imageFile = UploadedFile::getInstance($model, 'image');
            if ($imageFile) {
                $model->uploadImage($imageFile);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Товар успешно создан.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // Обработка загрузки изображения
            $imageFile = UploadedFile::getInstance($model, 'image');
            if ($imageFile) {
                $model->uploadImage($imageFile);
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Товар успешно обновлен.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Товар успешно удален.');

        return $this->redirect(['index']);
    }

    /**
     * AJAX создание нового товара
     * @return array
     */
    public function actionCreateAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $model = new Product();
        $model->load(Yii::$app->request->post());
        
        // Устанавливаем purchases_id из POST данных
        $purchasesId = Yii::$app->request->post('purchases_id');
        if ($purchasesId) {
            $model->purchases_id = $purchasesId;
        }
        
        // Обработка загрузки изображения
        $imageFile = UploadedFile::getInstance($model, 'image');
        
        if ($model->save()) {
            // Если есть изображение, загружаем его
            if ($imageFile) {
                $model->uploadImage($imageFile);
                $model->save(); // Сохраняем с именем файла
            }
            
            return [
                'success' => true,
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->description,
                'image' => $model->getImageUrl(),
                'warranty_period' => $model->warranty_period,
                'serial_number' => $model->serial_number,
                'message' => 'Товар успешно создан.'
            ];
        } else {
            return [
                'success' => false,
                'errors' => $model->errors,
                'message' => 'Ошибка при создании товара.'
            ];
        }
    }

    /**
     * AJAX получение списка товаров по категории
     * @return array
     */
    public function actionGetProductsByCategory()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $categoryId = Yii::$app->request->get('category_id');
        $products = Product::getProductsByCategory($categoryId);
        
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'image' => $product->getImageUrl()
            ];
        }
        
        return $result;
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый товар не найден.');
    }
}
