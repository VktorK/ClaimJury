<?php

namespace app\controllers;

use app\services\MoyaposylkaService;
use Yii;
use app\models\Claim;
use app\models\ClaimSearch;
use app\models\ClaimTemplate;
use app\models\Package;
use app\models\UserClaimTemplate;
use app\models\Purchase;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * ClaimController implements the CRUD actions for Claim model.
 */
class ClaimController extends Controller
{

    private MoyaposylkaService $_moyaposylkaService;
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
                        'actions' => ['get-templates', 'get-template-content', 'get-purchase-data', 'generate-docx', 'check-warranty', 'save-repair-info', 'save-defect-proof-info', 'update-template', 'check-tracking'],
                        'roles' => ['?', '@'], // Разрешаем доступ как авторизованным, так и неавторизованным пользователям
                    ],
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
                    'save-user-template' => ['POST'],
                    'delete-user-template' => ['POST'],
                    'toggle-favorite-template' => ['POST'],
                    'generate-docx' => ['POST'],
                    'update-template' => ['POST'],
                'save-repair-info' => ['POST'],
                'save-defect-proof-info' => ['POST'],
                'update-template' => ['POST'],
                'check-tracking' => ['POST'],
                'create-draft' => ['POST'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, MoyaposylkaService $_moyaposylkaService,
                                $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_moyaposylkaService = $_moyaposylkaService;
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
        // Проверяем, есть ли уже черновик претензии для этой покупки
        $purchaseId = Yii::$app->request->post('Claim')['purchase_id'] ?? null;
        $existingClaimId = Yii::$app->request->post('existing_claim_id');
        $existingClaim = null;
        
        // Сначала проверяем по ID из скрытого поля
        if ($existingClaimId) {
            $existingClaim = Claim::find()
                ->where(['id' => $existingClaimId, 'user_id' => Yii::$app->user->id])
                ->one();
        }
        
        // Если не найден по ID, ищем по purchase_id
        if (!$existingClaim && $purchaseId) {
            $existingClaim = Claim::find()
                ->where(['purchase_id' => $purchaseId, 'user_id' => Yii::$app->user->id])
                ->andWhere(['status' => Claim::STATUS_PENDING])
                ->one();
        }
        
        if ($existingClaim) {
            // Обновляем существующий черновик
            $model = $existingClaim;
        } else {
            // Создаем новую претензию
            $model = new Claim();
        }

        if ($model->load(Yii::$app->request->post())) {
            // Обрабатываем HTML-теги в описании
            if (!empty($model->description)) {
                // Сохраняем HTML с форматированием, но очищаем от опасных тегов
                $model->description = $this->sanitizeHtml($model->description);
            }
            
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
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => false, 'message' => 'Нельзя удалить претензию в текущем статусе.'];
            }
            Yii::$app->session->setFlash('error', 'Нельзя удалить претензию в текущем статусе.');
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        $model->delete();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true, 'message' => 'Претензия успешно удалена.'];
        }
        
        Yii::$app->session->setFlash('success', 'Претензия успешно удалена.');
        
        // Всегда редиректим на страницу покупок, если это не AJAX-запрос
        return $this->redirect(['/purchase/index']);
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

    /**
     * Получить шаблоны претензий по типу (AJAX)
     * @return Response
     */
    public function actionGetTemplates()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $type = Yii::$app->request->get('type');
        if (!$type) {
            return ['success' => false, 'message' => 'Тип не указан'];
        }

        $userId = Yii::$app->user->id;
        $result = [];
        
        // Получаем типовые шаблоны
        $defaultTemplates = ClaimTemplate::getActiveByType($type);
        foreach ($defaultTemplates as $template) {
            $result[] = [
                'id' => 'default_' . $template->id,
                'name' => $template->name,
                'description' => $template->description,
                'type' => 'default',
                'is_favorite' => false,
            ];
        }
        
        // Получаем пользовательские шаблоны только если пользователь авторизован
        if ($userId) {
            $userTemplates = UserClaimTemplate::getActiveTemplatesByType($userId, $type);
            foreach ($userTemplates as $template) {
                $result[] = [
                    'id' => 'user_' . $template->id,
                    'name' => $template->name,
                    'description' => $template->description,
                    'type' => 'user',
                    'is_favorite' => $template->is_favorite,
                    'original_template_id' => $template->original_template_id,
                ];
            }
        }

        return ['success' => true, 'templates' => $result];
    }

    /**
     * Получить заполненный шаблон претензии (AJAX)
     * @return Response
     */
    public function actionGetTemplateContent()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $templateId = Yii::$app->request->get('template_id');
        $purchaseId = Yii::$app->request->get('purchase_id');
        
        if (!$templateId || !$purchaseId) {
            return ['success' => false, 'message' => 'Не указаны необходимые параметры'];
        }

        $purchase = \app\models\Purchase::findOne($purchaseId);
        if (!$purchase) {
            return ['success' => false, 'message' => 'Покупка не найдена'];
        }

        // Проверяем, что покупка принадлежит текущему пользователю (только если пользователь авторизован)
        if (Yii::$app->user->id && $purchase->user_id != Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Доступ запрещен'];
        }

        $template = null;
        $content = '';

        // Определяем тип шаблона по ID
        if (strpos($templateId, 'default_') === 0) {
            // Типовой шаблон
            $id = substr($templateId, 8); // Убираем 'default_'
            $template = ClaimTemplate::findOne($id);
            if ($template) {
                $content = $template->fillTemplate($purchase, null);
            }
        } elseif (strpos($templateId, 'user_') === 0) {
            // Пользовательский шаблон (только для авторизованных пользователей)
            if (Yii::$app->user->id) {
                $id = substr($templateId, 5); // Убираем 'user_'
                $userTemplate = UserClaimTemplate::findOne($id);
                if ($userTemplate && $userTemplate->user_id == Yii::$app->user->id) {
                    $content = $userTemplate->fillTemplate($purchase, null);
                }
            }
        }
        
        if (!$template && !$content) {
            return ['success' => false, 'message' => 'Шаблон не найден'];
        }

        return ['success' => true, 'content' => $content];
    }

    /**
     * Генерация DOCX файла претензии
     */
    public function actionGenerateDocx()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        
        $content = Yii::$app->request->post('content');
        $purchaseId = Yii::$app->request->post('purchase_id');
        $claimType = Yii::$app->request->post('claim_type');
        
        if (!$content) {
            throw new \yii\web\BadRequestHttpException('Содержимое не указано');
        }
        
        // Получаем данные о покупке для формирования имени файла
        $purchase = null;
        $buyerName = '';
        $sellerName = '';
        
        // Логируем для отладки
        Yii::info("Purchase ID: " . $purchaseId, 'claim');
        Yii::info("Claim Type: " . $claimType, 'claim');
        
        if ($purchaseId) {
            $purchase = \app\models\Purchase::findOne($purchaseId);
            if ($purchase) {
                Yii::info("Purchase found: " . $purchase->id, 'claim');
                
                // Получаем данные покупателя
                if ($purchase->buyer) {
                    $buyerName = trim($purchase->buyer->firstName . ' ' . 
                                    $purchase->buyer->middleName . ' ' . 
                                    $purchase->buyer->lastName);
                    Yii::info("Buyer name before transliteration: " . $buyerName, 'claim');
                    
                    // Транслитерируем в английский и заменяем пробелы на подчеркивания
                    $buyerName = $this->transliterate($buyerName);
                    Yii::info("Buyer name after transliteration: " . $buyerName, 'claim');
                } else {
                    Yii::info("No buyer found for purchase", 'claim');
                }
                
                // Получаем данные продавца
                if ($purchase->seller) {
                    $sellerName = $purchase->seller->title;
                    Yii::info("Seller name before transliteration: " . $sellerName, 'claim');
                    
                    // Транслитерируем в английский и убираем спецсимволы
                    $sellerName = $this->transliterate($sellerName);
                    Yii::info("Seller name after transliteration: " . $sellerName, 'claim');
                } else {
                    Yii::info("No seller found for purchase", 'claim');
                }
            } else {
                Yii::info("Purchase not found with ID: " . $purchaseId, 'claim');
            }
        } else {
            Yii::info("No purchase ID provided", 'claim');
        }

        try {
            // Создаем новый документ
            $phpWord = new PhpWord();
            
            // Настройки документа
            $phpWord->setDefaultFontName('Times New Roman');
            $phpWord->setDefaultFontSize(12);
            
            // Добавляем секцию
            $section = $phpWord->addSection([
                'marginTop' => 1134,    // 2 см
                'marginRight' => 1134,  // 2 см
                'marginBottom' => 1134, // 2 см
                'marginLeft' => 1134,   // 2 см
            ]);
            
            // Заголовок
            $section->addText(
                'ПРЕТЕНЗИЯ',
                [
                    'name' => 'Times New Roman',
                    'size' => 16,
                    'bold' => true,
                ],
                [
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'spaceAfter' => 240,
                ]
            );
            
            // Пустая строка
            $section->addTextBreak(1);
            
            // Конвертируем HTML в простой текст
            $plainText = strip_tags($content);
            $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
            
            // Разбиваем на параграфы
            $paragraphs = explode("\n", $plainText);
            
            foreach ($paragraphs as $paragraph) {
                $paragraph = trim($paragraph);
                if (!empty($paragraph)) {
                    $section->addText(
                        $paragraph,
                        [
                            'name' => 'Times New Roman',
                            'size' => 12,
                        ],
                        [
                            'spaceAfter' => 120,
                        ]
                    );
                }
            }
            
            // Пустая строка
            $section->addTextBreak(1);
            
            // Дата
            $section->addText(
                'Дата: ' . date('d.m.Y'),
                [
                    'name' => 'Times New Roman',
                    'size' => 12,
                ],
                [
                    'spaceBefore' => 240,
                ]
            );
            
            // Формируем имя файла в формате: тип_претензии_покупатель_продавец
            $filename = '';
            
            // Определяем тип претензии
            if ($claimType) {
                switch ($claimType) {
                    case 'repair':
                        $filename = 'repair';
                        break;
                    case 'refund':
                        $filename = 'refund';
                        break;
                    case 'replacement':
                        $filename = 'replacement';
                        break;
                    case 'custom':
                        $filename = 'custom';
                        break;
                    default:
                        $filename = 'claim';
                        break;
                }
            } else {
                $filename = 'claim';
            }
            
            if ($buyerName) {
                $filename .= '_' . $buyerName;
            }
            if ($sellerName) {
                $filename .= '_' . $sellerName;
            }
            $filename .= '.docx';
            
            // Логируем итоговое имя файла
            Yii::info("Final filename: " . $filename, 'claim');
            
            // Устанавливаем заголовки для скачивания
            Yii::$app->response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            Yii::$app->response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
            Yii::$app->response->headers->set('Cache-Control', 'max-age=0');
            
            // Создаем временный файл
            $tempFile = tempnam(sys_get_temp_dir(), 'claim_');
            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempFile);
            
            // Отправляем файл
            Yii::$app->response->data = file_get_contents($tempFile);
            
            // Удаляем временный файл
            unlink($tempFile);
            
        } catch (\Exception $e) {
            Yii::error('Ошибка при создании DOCX файла: ' . $e->getMessage(), 'claim');
            throw new \yii\web\ServerErrorHttpException('Ошибка при создании документа');
        }
    }

    /**
     * Транслитерация русского текста в английский
     */
    private function transliterate($text)
    {
        // Убираем лишние пробелы и приводим к нижнему регистру
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text);
        
        $transliteration = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
            'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'yo',
            'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm',
            'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u',
            'Ф' => 'f', 'Х' => 'h', 'Ц' => 'ts', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch',
            'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya',
            ' ' => '_', '-' => '_', '_' => '_', '"' => '', "'" => '', '«' => '', '»' => '',
            '№' => 'N', '№' => 'N'
        ];

        $result = strtr($text, $transliteration);
        
        // Убираем все символы, кроме букв, цифр и подчеркиваний
        $result = preg_replace('/[^a-zA-Z0-9_]/', '', $result);
        
        // Убираем множественные подчеркивания
        $result = preg_replace('/_+/', '_', $result);
        
        // Убираем подчеркивания в начале и конце
        $result = trim($result, '_');
        
        return $result;
    }

    /**
     * Очищает HTML от опасных тегов, сохраняя форматирование
     * @param string $html HTML-контент
     * @return string Безопасный HTML
     */
    private function sanitizeHtml($html)
    {
        // Разрешенные теги для форматирования
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><div><span>';
        
        // Очищаем от опасных тегов, оставляя только разрешенные
        $cleanHtml = strip_tags($html, $allowedTags);
        
        // Убираем пустые теги
        $cleanHtml = preg_replace('/<(\w+)[^>]*>\s*<\/\1>/', '', $cleanHtml);
        
        // Нормализуем пробелы
        $cleanHtml = preg_replace('/\s+/', ' ', $cleanHtml);
        $cleanHtml = trim($cleanHtml);
        
        return $cleanHtml;
    }

    /**
     * Получить HTML контент претензии для редактирования (AJAX)
     * @return Response
     */
    public function actionGetClaimHtml()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->get('id');
        
        if (!$id) {
            return ['success' => false, 'message' => 'ID не указан'];
        }
        
        $claim = Claim::findOne($id);
        if (!$claim) {
            return ['success' => false, 'message' => 'Претензия не найдена'];
        }
        
        // Проверяем права доступа
        if ($claim->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для просмотра этой претензии'];
        }
        
        return [
            'success' => true, 
            'html' => $claim->description ?: '',
            'message' => 'HTML контент получен'
        ];
    }

    /**
     * Получить данные покупки для модального окна (AJAX)
     * @return Response
     */
    public function actionGetPurchaseData()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $purchaseId = Yii::$app->request->get('id');
        
        if (!$purchaseId) {
            return ['success' => false, 'message' => 'Не указан ID покупки'];
        }

        $purchase = \app\models\Purchase::findOne($purchaseId);
        
        if (!$purchase) {
            return ['success' => false, 'message' => 'Покупка не найдена'];
        }

        // Проверяем, что покупка принадлежит текущему пользователю
        if ($purchase->user_id != Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Доступ запрещен'];
        }

        $seller = $purchase->seller;
        $buyer = $purchase->buyer;
        
        return [
            'success' => true,
            'seller' => [
                'title' => $seller ? $seller->title : '',
                'address' => $seller ? $seller->address : '',
                'ogrn' => $seller ? $seller->ogrn : '',
            ],
            'buyer' => [
                'fullName' => $buyer ? $buyer->getFullName() : '',
                'firstName' => $buyer ? $buyer->firstName : '',
                'lastName' => $buyer ? $buyer->lastName : '',
                'middleName' => $buyer ? $buyer->middleName : '',
                'address' => $buyer ? $buyer->address : '',
            ],
            'product' => [
                'title' => $purchase->product ? $purchase->product->title : '',
                'serial_number' => $purchase->product ? $purchase->product->serial_number : '',
            ],
            'purchase' => [
                'date' => $purchase->purchase_date ? Yii::$app->formatter->asDate($purchase->purchase_date, 'php:d.m.Y') : '',
                'amount' => $purchase->amount ? Yii::$app->formatter->asCurrency($purchase->amount) : '',
                'warranty' => $purchase->warranty_period ? $purchase->warranty_period . ' дней' : '',
            ]
        ];
    }

    /**
     * Сохранить пользовательский шаблон (AJAX)
     * @return Response
     */
    public function actionSaveUserTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Логируем входящие данные для отладки
        Yii::info('SaveUserTemplate called with POST data: ' . json_encode($_POST), 'claim');
        
        $originalTemplateId = Yii::$app->request->post('original_template_id');
        $name = Yii::$app->request->post('name');
        $content = Yii::$app->request->post('content');
        $type = Yii::$app->request->post('type');
        
        Yii::info("Parsed data: name=$name, type=$type, contentLength=" . strlen($content), 'claim');
        
        if (!$name || !$content || !$type) {
            return ['success' => false, 'message' => 'Не указаны необходимые параметры'];
        }

        $userId = Yii::$app->user->id;
        $userTemplate = null;

        if ($originalTemplateId) {
            // Создаем на основе типового шаблона
            $userTemplate = UserClaimTemplate::createFromOriginal($userId, $originalTemplateId, $name, $content);
        } else {
            // Создаем полностью пользовательский шаблон
            $userTemplate = UserClaimTemplate::createCustom($userId, $name, $type, $content);
        }

        if ($userTemplate) {
            Yii::info('Template saved successfully with ID: ' . $userTemplate->id, 'claim');
            return [
                'success' => true, 
                'message' => 'Шаблон успешно сохранен',
                'template_id' => 'user_' . $userTemplate->id
            ];
        } else {
            Yii::error('Failed to save template for user: ' . $userId, 'claim');
            return ['success' => false, 'message' => 'Ошибка сохранения шаблона'];
        }
    }

    /**
     * Удалить пользовательский шаблон (AJAX)
     * @return Response
     */
    public function actionDeleteUserTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $templateId = Yii::$app->request->post('template_id');
        
        if (!$templateId) {
            return ['success' => false, 'message' => 'ID шаблона не указан'];
        }

        $userTemplate = UserClaimTemplate::findOne($templateId);
        
        if (!$userTemplate || $userTemplate->user_id != Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Шаблон не найден или доступ запрещен'];
        }

        if ($userTemplate->delete()) {
            return ['success' => true, 'message' => 'Шаблон успешно удален'];
        } else {
            return ['success' => false, 'message' => 'Ошибка удаления шаблона'];
        }
    }

    /**
     * Переключить статус избранного шаблона (AJAX)
     * @return Response
     */
    public function actionToggleFavoriteTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $templateId = Yii::$app->request->post('template_id');
        
        if (!$templateId) {
            return ['success' => false, 'message' => 'ID шаблона не указан'];
        }

        $userTemplate = UserClaimTemplate::findOne($templateId);
        
        if (!$userTemplate || $userTemplate->user_id != Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Шаблон не найден или доступ запрещен'];
        }

        $userTemplate->is_favorite = !$userTemplate->is_favorite;
        
        if ($userTemplate->save()) {
            return [
                'success' => true, 
                'message' => $userTemplate->is_favorite ? 'Шаблон добавлен в избранное' : 'Шаблон удален из избранного',
                'is_favorite' => $userTemplate->is_favorite
            ];
        } else {
            return ['success' => false, 'message' => 'Ошибка обновления шаблона'];
        }
    }

    /**
     * Обновление шаблона претензии (AJAX)
     */
    public function actionUpdateTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->get('id');
        $description = Yii::$app->request->post('description');
        
        if (!$id || !$description) {
            return ['success' => false, 'message' => 'Не указаны обязательные параметры'];
        }
        
        $claim = Claim::findOne($id);
        if (!$claim) {
            return ['success' => false, 'message' => 'Претензия не найдена'];
        }
        
        // Проверяем права доступа
        if ($claim->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для редактирования этой претензии'];
        }
        
        try {
            // Отладочная информация
            Yii::info('Обновление текста претензии ID: ' . $id, 'claim');
            Yii::info('Полученное описание: ' . $description, 'claim');
            
            // Сохраняем HTML с форматированием, но очищаем от опасных тегов
            $claim->description = $this->sanitizeHtml($description);
            Yii::info('Описание после очистки: ' . $claim->description, 'claim');
            
            if ($claim->save()) {
                Yii::info('Текст претензии успешно сохранен', 'claim');
                return ['success' => true, 'message' => 'Шаблон успешно обновлен'];
            } else {
                Yii::error('Ошибки при сохранении текста претензии: ' . json_encode($claim->errors), 'claim');
                return ['success' => false, 'message' => 'Ошибка при сохранении: ' . implode(', ', $claim->getFirstErrors())];
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка при обновлении шаблона претензии: ' . $e->getMessage(), 'claim');
            return ['success' => false, 'message' => 'Ошибка при сохранении шаблона'];
        }
    }

    /**
     * Проверка гарантийного срока и срока подачи претензии (AJAX)
     */
    public function actionCheckWarranty()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $purchaseId = Yii::$app->request->get('purchase_id');
        
        Yii::info('Проверка гарантийного срока для покупки: ' . $purchaseId, 'claim');
        
        if (!$purchaseId) {
            Yii::info('Не указан ID покупки', 'claim');
            return ['success' => false, 'message' => 'Не указан ID покупки'];
        }
        
        $purchase = Purchase::findOne($purchaseId);
        if (!$purchase) {
            Yii::info('Покупка не найдена: ' . $purchaseId, 'claim');
            return ['success' => false, 'message' => 'Покупка не найдена'];
        }
        
        // Проверяем права доступа
        if ($purchase->user_id !== Yii::$app->user->id) {
            Yii::info('Нет прав для просмотра покупки: ' . $purchaseId . ', пользователь: ' . Yii::$app->user->id, 'claim');
            return ['success' => false, 'message' => 'Нет прав для просмотра этой покупки'];
        }
        
        try {
            $warrantyExpired = $purchase->isWarrantyExpired();
            $appealDeadlineExpired = $purchase->isAppealDeadlineExpired();
            $remainingDays = $purchase->getRemainingAppealDays();
            $appealDeadlineDate = $purchase->getFormattedAppealDeadlineDate();
            
            Yii::info('Результаты проверки: warranty_expired=' . ($warrantyExpired ? 'true' : 'false') . 
                     ', appeal_deadline_expired=' . ($appealDeadlineExpired ? 'true' : 'false') . 
                     ', remaining_days=' . $remainingDays . 
                     ', appeal_deadline_date=' . $appealDeadlineDate, 'claim');
            
            return [
                'success' => true,
                'warranty_expired' => $warrantyExpired,
                'appeal_deadline_expired' => $appealDeadlineExpired,
                'remaining_days' => $remainingDays,
                'appeal_deadline_date' => $appealDeadlineDate
            ];
        } catch (\Exception $e) {
            Yii::error('Ошибка при проверке гарантийного срока: ' . $e->getMessage(), 'claim');
            return ['success' => false, 'message' => 'Ошибка при проверке гарантийного срока'];
        }
    }

    /**
     * Создание черновика претензии (AJAX)
     */
    public function actionCreateDraft()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $purchaseId = Yii::$app->request->post('purchase_id');
        $claimType = Yii::$app->request->post('claim_type');
        
        if (!$purchaseId) {
            return ['success' => false, 'message' => 'Не указан ID покупки'];
        }
        
        $purchase = Purchase::findOne($purchaseId);
        if (!$purchase) {
            return ['success' => false, 'message' => 'Покупка не найдена'];
        }
        
        // Проверяем права доступа
        if ($purchase->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для создания претензии по этой покупке'];
        }
        
        try {
            $claim = new Claim();
            $claim->user_id = Yii::$app->user->id;
            $claim->purchase_id = $purchaseId;
            $claim->claim_type = $claimType ?: Claim::TYPE_CUSTOM;
            $claim->claim_date = time();
            $claim->status = Claim::STATUS_PENDING;
            
            if ($claim->save()) {
                return ['success' => true, 'claim_id' => $claim->id, 'message' => 'Черновик претензии создан'];
            } else {
                return ['success' => false, 'message' => 'Ошибка при создании черновика претензии'];
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка при создании черновика претензии: ' . $e->getMessage(), 'claim');
            return ['success' => false, 'message' => 'Ошибка при создании черновика претензии'];
        }
    }

    /**
     * Сохранение информации о ремонте (AJAX)
     */
    public function actionSaveRepairInfo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $claimId = Yii::$app->request->post('claim_id');
        $wasRepairedOfficially = Yii::$app->request->post('was_repaired_officially');
        $repairDocumentDescription = Yii::$app->request->post('repair_document_description');
        $repairDocumentDate = Yii::$app->request->post('repair_document_date');
        $defectDescription = Yii::$app->request->post('defect_description');
        
        if (!$claimId) {
            return ['success' => false, 'message' => 'Не указан ID претензии'];
        }
        
        $claim = Claim::findOne($claimId);
        if (!$claim) {
            return ['success' => false, 'message' => 'Претензия не найдена'];
        }
        
        // Проверяем права доступа
        if ($claim->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для редактирования этой претензии'];
        }
        
        try {
        
        $claim->was_repaired_officially = (bool)$wasRepairedOfficially;
        $claim->repair_document_description = $repairDocumentDescription;
        $claim->repair_document_date = $repairDocumentDate;
        
        // Сохраняем описание недостатка в соответствующее поле
        if ($wasRepairedOfficially) {
            $claim->repair_defect_description = $defectDescription;
        } else {
            $claim->current_defect_description = $defectDescription;
        }
            
            if ($claim->save()) {
                return ['success' => true, 'message' => 'Информация о ремонте сохранена'];
            } else {
                return ['success' => false, 'message' => 'Ошибка при сохранении информации о ремонте'];
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка при сохранении информации о ремонте: ' . $e->getMessage(), 'claim');
            return ['success' => false, 'message' => 'Ошибка при сохранении информации о ремонте'];
        }
    }

    /**
     * Сохранение информации о доказательствах недостатка (AJAX)
     */
    public function actionSaveDefectProofInfo()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $claimId = Yii::$app->request->post('claim_id');
        $defectProofType = Yii::$app->request->post('defect_proof_type');
        $defectProofDocumentDescription = Yii::$app->request->post('defect_proof_document_description');
        $defectProofDocumentDate = Yii::$app->request->post('defect_proof_document_date');
        $defectDescription = Yii::$app->request->post('defect_description');
        $defectSimilarity = Yii::$app->request->post('defect_similarity');
        
        if (!$claimId) {
            return ['success' => false, 'message' => 'Не указан ID претензии'];
        }
        
        $claim = Claim::findOne($claimId);
        if (!$claim) {
            return ['success' => false, 'message' => 'Претензия не найдена'];
        }
        
        // Проверяем права доступа
        if ($claim->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для редактирования этой претензии'];
        }
        
        try {
            
            $claim->defect_proof_type = $defectProofType;
            $claim->defect_proof_document_description = $defectProofDocumentDescription;
            $claim->defect_proof_document_date = $defectProofDocumentDate;
            $claim->defect_similarity = $defectSimilarity ? (bool)$defectSimilarity : null;
            
            // Сохраняем описание недостатка в соответствующее поле
            if ($defectProofType === 'quality_check' || $defectProofType === 'independent_expertise') {
                $claim->expertise_defect_description = $defectDescription;
            }
            
            if ($claim->save()) {
                return ['success' => true, 'message' => 'Информация о доказательствах недостатка сохранена'];
            } else {
                return ['success' => false, 'message' => 'Ошибка при сохранении информации о доказательствах недостатка'];
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка при сохранении информации о доказательствах недостатка: ' . $e->getMessage(), 'claim');
            return ['success' => false, 'message' => 'Ошибка при сохранении информации о доказательствах недостатка'];
        }
    }

    /**
     * Проверка статуса отслеживания
     */
    public function actionCheckTracking()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $trackingNumber = Yii::$app->request->post('tracking_number');
        $claimId = Yii::$app->request->post('claim_id');

        if (!$trackingNumber || !$claimId) {
            return ['success' => false, 'message' => 'Не указаны обязательные параметры'];
        }

        $claim = Claim::findOne($claimId);
        if (!$claim) {
            return ['success' => false, 'message' => 'Претензия не найдена'];
        }

        if ($claim->user_id !== Yii::$app->user->id) {
            return ['success' => false, 'message' => 'Нет прав для проверки статуса этой претензии'];
        }

        try {
            $carrier = $this->_moyaposylkaService->detectCarrier($trackingNumber);

            // 1. Сначала добавляем трекер
            $addResult = $this->_moyaposylkaService->addTracker($carrier, $claimId, $trackingNumber);
            if (!$addResult) {
                return [
                    'success' => false,
                    'message' => 'Не удалось добавить трекер для отслеживания'
                ];
            }

            // 2. Получаем информацию
            $infoResult = $this->_moyaposylkaService->getTrackerInfo($carrier, $trackingNumber);

            // 3. Обрабатываем возможные сценарии
            if ($infoResult === false) {
                // Случай 1: Произошла реальная ошибка API
                return [
                    'success' => false,
                    'message' => 'Ошибка при получении информации от сервиса отслеживания'
                ];
            }

            if (isset($infoResult['success']) && $infoResult['success'] === false) {
                // Случай 2: Трекер добавлен, но данных еще нет (НОРМАЛЬНАЯ СИТУАЦИЯ)
                $package = Package::createOrUpdate(
                    $trackingNumber,
                    Package::STATUS_PENDING, // Специальный статус "в обработке"
                    ['message' => $infoResult['message'] ?? 'Данные в обработке'],
                    $claim->id
                );

                return [
                    'success' => true,
                    'status' => 'pending',
                    'tracking_status' => 'Ожидание данных...',
                    'tracker_info' => [
                        'status' => 'pending',
                        'message' => $infoResult['message'] ?? 'Трекер добавлен. Данные появятся в течение 24 часов.',
                        'carrier' => $carrier,
                        'tracking_number' => $trackingNumber
                    ],
                    'package_id' => $package->id,
                    'message' => 'Трекер добавлен в систему отслеживания'
                ];
            }

            if (isset($infoResult['success']) && $infoResult['success'] === true) {
                // Случай 3: Данные успешно получены
                $trackerInfo = $this->formatTrackerInfoForModal($infoResult, $carrier, $trackingNumber);

                $package = Package::createOrUpdate(
                    $trackingNumber,
                    $this->mapApiStatusToPackageStatus($infoResult),
                    $infoResult,
                    $claim->id
                );

                return [
                    'success' => true,
                    'status' => 'success',
                    'tracking_status' => $this->formatTrackingStatus($infoResult),
                    'tracker_info' => $trackerInfo,
                    'package_id' => $package->id,
                    'message' => 'Статус отслеживания получен'
                ];
            }

            // Случай 4: Непредвиденный формат ответа
            Yii::error("Неожиданный формат ответа API: " . Json::encode($infoResult), 'moyaposylka');
            return [
                'success' => false,
                'message' => 'Непредвиденный ответ от сервиса отслеживания'
            ];

        } catch (\Exception $e) {
            Yii::error('Ошибка при проверке статуса отслеживания: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString(), 'claim');
            return [
                'success' => false,
                'message' => 'Ошибка при проверке статуса отслеживания'
            ];
        }
    }


    /**
     * Форматирует статус отслеживания из ответа API
     */
    private function formatTrackingStatus($trackerInfo)
    {
        if (!isset($trackerInfo['status'])) {
            return 'Статус не определен';
        }
        
        $status = $trackerInfo['status'];
        
        // Проверяем, что статус является строкой
        if (is_array($status)) {
            $status = isset($status['name']) ? $status['name'] : 'Неизвестный статус';
        } elseif (!is_string($status)) {
            $status = 'Неизвестный статус';
        }
        
        // Маппинг статусов на русский язык
        $statusMap = [
            'created' => 'Отправление создано',
            'accepted' => 'Отправление принято',
            'in_transit' => 'Отправление в пути',
            'out_for_delivery' => 'Отправление доставляется',
            'delivered' => 'Отправление доставлено',
            'exception' => 'Проблема с доставкой',
            'returned' => 'Отправление возвращено',
            'cancelled' => 'Отправление отменено'
        ];
        
        return $statusMap[$status] ?? $status;
    }

    /**
     * Форматирует информацию о трекере для отображения в модальном окне
     * @param array $infoResult Результат от API
     * @param string $carrier Перевозчик
     * @param string $trackingNumber Номер отслеживания
     * @return array
     */
    private function formatTrackerInfoForModal($infoResult, $carrier, $trackingNumber)
    {
        $trackerInfo = [
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
            'status' => $this->formatTrackingStatus($infoResult),
            'last_update' => time() * 1000, // В миллисекундах для JavaScript
        ];

        // Добавляем информацию об отправителе и получателе
        if (isset($infoResult['sender']) && is_array($infoResult['sender'])) {
            $trackerInfo['sender'] = [
                'name' => is_string($infoResult['sender']['name'] ?? null) ? $infoResult['sender']['name'] : 'Не указано',
                'address' => is_string($infoResult['sender']['address'] ?? null) ? $infoResult['sender']['address'] : 'Не указано',
                'phone' => is_string($infoResult['sender']['phone'] ?? null) ? $infoResult['sender']['phone'] : 'Не указано'
            ];
        }

        if (isset($infoResult['recipient']) && is_array($infoResult['recipient'])) {
            $trackerInfo['recipient'] = [
                'name' => is_string($infoResult['recipient']['name'] ?? null) ? $infoResult['recipient']['name'] : 'Не указано',
                'address' => is_string($infoResult['recipient']['address'] ?? null) ? $infoResult['recipient']['address'] : 'Не указано',
                'phone' => is_string($infoResult['recipient']['phone'] ?? null) ? $infoResult['recipient']['phone'] : 'Не указано'
            ];
        }

        // Добавляем даты
        if (isset($infoResult['ship_date'])) {
            $trackerInfo['ship_date'] = $infoResult['ship_date'];
        }

        if (isset($infoResult['estimated_delivery'])) {
            $trackerInfo['estimated_delivery'] = $infoResult['estimated_delivery'];
        }

        // Добавляем историю статусов
        if (isset($infoResult['history']) && is_array($infoResult['history'])) {
            $trackerInfo['history'] = array_map(function($item) {
                if (!is_array($item)) {
                    return [
                        'date' => time() * 1000,
                        'status' => 'Неизвестный статус',
                        'location' => null,
                        'description' => null
                    ];
                }
                
                return [
                    'date' => isset($item['date']) ? $item['date'] : time() * 1000,
                    'status' => is_string($item['status'] ?? null) ? $item['status'] : 'Неизвестный статус',
                    'location' => is_string($item['location'] ?? null) ? $item['location'] : null,
                    'description' => is_string($item['description'] ?? null) ? $item['description'] : null
                ];
            }, $infoResult['history']);
        }

        return $trackerInfo;
    }

    /**
     * Маппинг статуса из API на статус пакета
     * @param array $apiResult Результат от API
     * @return int
     */
    private function mapApiStatusToPackageStatus($apiResult)
    {
        if (!isset($apiResult['status'])) {
            return Package::STATUS_PENDING;
        }

        $status = $apiResult['status'];
        
        // Если статус массив, берем name
        if (is_array($status)) {
            $status = $status['name'] ?? 'pending';
        }

        $statusMap = [
            'created' => Package::STATUS_PENDING,
            'pending' => Package::STATUS_PENDING,
            'accepted' => Package::STATUS_ACCEPTED,
            'in_transit' => Package::STATUS_IN_TRANSIT,
            'out_for_delivery' => Package::STATUS_OUT_FOR_DELIVERY,
            'delivered' => Package::STATUS_DELIVERED,
            'exception' => Package::STATUS_EXCEPTION,
            'returned' => Package::STATUS_RETURNED,
            'cancelled' => Package::STATUS_CANCELLED,
        ];

        return $statusMap[$status] ?? Package::STATUS_PENDING;
    }

//    public function actionDebugTracking()
//    {
//        $trackingNumber = '80086309440644'; // тестовый трек
//        $carrier = $this->_moyaposylkaService->detectCarrier($trackingNumber);
//
//        $result = $this->_moyaposylkaService->debugTrackerInfo($carrier, $trackingNumber);
//
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return $result;
//    }

    public function actionDebugTracking()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Тестируем соединение
            $connectionTest = $this->_moyaposylkaService->testConnection();

            // Тестируем конкретный трек
            $testTrack = '80086309440644';
            $carrier = 'russian-post'; // или другой перевозчик
            $trackerInfo = $this->_moyaposylkaService->getTrackerInfo($carrier, $testTrack);

            return [
                'connection_test' => $connectionTest,
                'tracker_info' => $trackerInfo,
                'service_config' => [
                    'base_url' => Yii::$app->params['baseUrl'],
                    'has_token' => !empty(Yii::$app->params['apiToken'])
                ]
            ];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

}
