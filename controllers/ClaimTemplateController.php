<?php

namespace app\controllers;

use Yii;
use app\models\ClaimTemplate;
use app\models\ClaimTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * ClaimTemplateController implements the CRUD actions for ClaimTemplate model.
 */
class ClaimTemplateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ClaimTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClaimTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClaimTemplate model.
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
     * Creates a new ClaimTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClaimTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Шаблон претензии успешно создан.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ClaimTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Шаблон претензии успешно обновлен.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ClaimTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Шаблон претензии успешно удален.');
        return $this->redirect(['index']);
    }

    /**
     * Preview template with sample data
     * @param integer $id
     * @return mixed
     */
    public function actionPreview($id)
    {
        $model = $this->findModel($id);
        
        // Создаем тестовые данные для предварительного просмотра
        $sampleData = $this->getSampleData();
        
        // Заполняем шаблон тестовыми данными
        $previewContent = $this->fillTemplateWithSampleData($model->template_content, $sampleData);
        
        return $this->render('preview', [
            'model' => $model,
            'previewContent' => $previewContent,
            'sampleData' => $sampleData,
        ]);
    }

    /**
     * Get available placeholders
     * @return array
     */
    public function actionGetPlaceholders()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $placeholders = [
            'seller' => [
                'name' => 'Данные продавца',
                'placeholders' => [
                    '{SELLER_NAME}' => 'Название продавца',
                    '{SELLER_OGRN}' => 'ОГРН продавца',
                    '{SELLER_ADDRESS}' => 'Адрес продавца',
                    '{SELLER_PHONE}' => 'Телефон продавца',
                    '{SELLER_EMAIL}' => 'Email продавца',
                ]
            ],
            'buyer' => [
                'name' => 'Данные покупателя',
                'placeholders' => [
                    '{BUYER_FULL_NAME}' => 'Полное имя покупателя',
                    '{BUYER_FIRST_NAME}' => 'Имя покупателя',
                    '{BUYER_LAST_NAME}' => 'Фамилия покупателя',
                    '{BUYER_MIDDLE_NAME}' => 'Отчество покупателя',
                    '{BUYER_ADDRESS}' => 'Адрес покупателя',
                    '{BUYER_PHONE}' => 'Телефон покупателя',
                    '{BUYER_EMAIL}' => 'Email покупателя',
                ]
            ],
            'product' => [
                'name' => 'Данные товара',
                'placeholders' => [
                    '{PRODUCT_NAME}' => 'Название товара',
                    '{PRODUCT_MODEL}' => 'Модель товара',
                    '{PRODUCT_SERIAL}' => 'Серийный номер',
                    '{PRODUCT_CATEGORY}' => 'Категория товара',
                ]
            ],
            'purchase' => [
                'name' => 'Данные покупки',
                'placeholders' => [
                    '{PURCHASE_DATE}' => 'Дата покупки',
                    '{PURCHASE_PRICE}' => 'Цена покупки',
                    '{PURCHASE_WARRANTY}' => 'Гарантийный период',
                ]
            ],
            'defect' => [
                'name' => 'Информация о недостатках',
                'placeholders' => [
                    '{CURRENT_DEFECT_DESCRIPTION}' => 'Описание текущего недостатка',
                    '{REPAIR_DEFECT_DESCRIPTION}' => 'Описание недостатка при ремонте',
                    '{EXPERTISE_DEFECT_DESCRIPTION}' => 'Описание недостатка по экспертизе',
                    '{GENERAL_DEFECT_DESCRIPTION}' => 'Общее описание недостатка',
                ]
            ],
            'repair' => [
                'name' => 'Информация о ремонте',
                'placeholders' => [
                    '{WAS_REPAIRED_OFFICIALLY}' => 'Был ли товар в ремонте',
                    '{REPAIR_DOCUMENT_DESCRIPTION}' => 'Описание документа о ремонте',
                    '{REPAIR_DOCUMENT_DATE}' => 'Дата документа о ремонте',
                ]
            ],
            'proof' => [
                'name' => 'Доказательства недостатка',
                'placeholders' => [
                    '{DEFECT_PROOF_TYPE}' => 'Тип доказательства недостатка',
                    '{DEFECT_PROOF_DOCUMENT_DESCRIPTION}' => 'Описание документа доказательства',
                    '{DEFECT_PROOF_DOCUMENT_DATE}' => 'Дата документа доказательства',
                ]
            ],
            'system' => [
                'name' => 'Системные данные',
                'placeholders' => [
                    '{CURRENT_DATE}' => 'Текущая дата',
                ]
            ]
        ];
        
        return $placeholders;
    }

    /**
     * Finds the ClaimTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClaimTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClaimTemplate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый шаблон не найден.');
    }

    /**
     * Get sample data for preview
     * @return array
     */
    private function getSampleData()
    {
        return [
            'seller' => [
                'name' => 'ООО "ТехноМир"',
                'ogrn' => '1234567890123',
                'address' => 'г. Москва, ул. Техническая, д. 1',
                'phone' => '+7 (495) 123-45-67',
                'email' => 'info@technomir.ru',
            ],
            'buyer' => [
                'full_name' => 'Иванов Иван Иванович',
                'first_name' => 'Иван',
                'last_name' => 'Иванов',
                'middle_name' => 'Иванович',
                'address' => 'г. Москва, ул. Потребительская, д. 10, кв. 5',
                'phone' => '+7 (999) 123-45-67',
                'email' => 'ivanov@example.com',
            ],
            'product' => [
                'name' => 'Смартфон Samsung Galaxy S21',
                'model' => 'SM-G991B',
                'serial' => 'SN123456789',
                'category' => 'Смартфоны',
            ],
            'purchase' => [
                'date' => '15.03.2024',
                'price' => '89 990 ₽',
                'warranty' => '365 дней',
            ],
            'defect' => [
                'current' => 'Не работает камера, экран мигает',
                'repair' => 'Камера не фокусируется, экран показывает полосы',
                'expertise' => 'Производственный брак матрицы камеры',
                'general' => 'Неисправность камеры и дисплея',
            ],
            'repair' => [
                'was_repaired' => 'Да',
                'document_description' => 'Акт о проведении ремонта',
                'document_date' => '20.05.2024',
            ],
            'proof' => [
                'type' => 'Экспертное заключение',
                'document_description' => 'Заключение независимой экспертизы',
                'document_date' => '25.05.2024',
            ],
            'system' => [
                'current_date' => date('d.m.Y'),
            ]
        ];
    }

    /**
     * Fill template with sample data
     * @param string $template
     * @param array $data
     * @return string
     */
    private function fillTemplateWithSampleData($template, $data)
    {
        $content = $template;
        
        // Данные продавца
        $content = str_replace('{SELLER_NAME}', $data['seller']['name'], $content);
        $content = str_replace('{SELLER_OGRN}', $data['seller']['ogrn'], $content);
        $content = str_replace('{SELLER_ADDRESS}', $data['seller']['address'], $content);
        $content = str_replace('{SELLER_PHONE}', $data['seller']['phone'], $content);
        $content = str_replace('{SELLER_EMAIL}', $data['seller']['email'], $content);
        
        // Данные покупателя
        $content = str_replace('{BUYER_FULL_NAME}', $data['buyer']['full_name'], $content);
        $content = str_replace('{BUYER_FIRST_NAME}', $data['buyer']['first_name'], $content);
        $content = str_replace('{BUYER_LAST_NAME}', $data['buyer']['last_name'], $content);
        $content = str_replace('{BUYER_MIDDLE_NAME}', $data['buyer']['middle_name'], $content);
        $content = str_replace('{BUYER_ADDRESS}', $data['buyer']['address'], $content);
        $content = str_replace('{BUYER_PHONE}', $data['buyer']['phone'], $content);
        $content = str_replace('{BUYER_EMAIL}', $data['buyer']['email'], $content);
        
        // Данные товара
        $content = str_replace('{PRODUCT_NAME}', $data['product']['name'], $content);
        $content = str_replace('{PRODUCT_MODEL}', $data['product']['model'], $content);
        $content = str_replace('{PRODUCT_SERIAL}', $data['product']['serial'], $content);
        $content = str_replace('{PRODUCT_CATEGORY}', $data['product']['category'], $content);
        
        // Данные покупки
        $content = str_replace('{PURCHASE_DATE}', $data['purchase']['date'], $content);
        $content = str_replace('{PURCHASE_PRICE}', $data['purchase']['price'], $content);
        $content = str_replace('{PURCHASE_WARRANTY}', $data['purchase']['warranty'], $content);
        
        // Информация о ремонте
        $content = str_replace('{WAS_REPAIRED_OFFICIALLY}', $data['repair']['was_repaired'], $content);
        $content = str_replace('{REPAIR_DOCUMENT_DESCRIPTION}', $data['repair']['document_description'], $content);
        $content = str_replace('{REPAIR_DOCUMENT_DATE}', $data['repair']['document_date'], $content);
        
        // Информация о доказательствах недостатка
        $content = str_replace('{DEFECT_PROOF_TYPE}', $data['proof']['type'], $content);
        $content = str_replace('{DEFECT_PROOF_DOCUMENT_DESCRIPTION}', $data['proof']['document_description'], $content);
        $content = str_replace('{DEFECT_PROOF_DOCUMENT_DATE}', $data['proof']['document_date'], $content);
        
        // Различные типы описаний недостатков
        $content = str_replace('{REPAIR_DEFECT_DESCRIPTION}', $data['defect']['repair'], $content);
        $content = str_replace('{CURRENT_DEFECT_DESCRIPTION}', $data['defect']['current'], $content);
        $content = str_replace('{EXPERTISE_DEFECT_DESCRIPTION}', $data['defect']['expertise'], $content);
        $content = str_replace('{GENERAL_DEFECT_DESCRIPTION}', $data['defect']['general'], $content);
        
        // Обратная совместимость
        $content = str_replace('{DEFECT_DESCRIPTION}', $data['defect']['general'], $content);
        
        // Текущая дата
        $content = str_replace('{CURRENT_DATE}', $data['system']['current_date'], $content);
        
        return $content;
    }
}
