<?php

namespace app\controllers;

use Yii;
use app\models\Claim;
use app\models\ClaimSearch;
use app\models\ClaimTemplate;
use app\models\UserClaimTemplate;
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
                        'actions' => ['get-templates', 'get-template-content', 'get-purchase-data', 'generate-docx'],
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
                $content = $template->fillTemplate($purchase);
            }
        } elseif (strpos($templateId, 'user_') === 0) {
            // Пользовательский шаблон (только для авторизованных пользователей)
            if (Yii::$app->user->id) {
                $id = substr($templateId, 5); // Убираем 'user_'
                $userTemplate = UserClaimTemplate::findOne($id);
                if ($userTemplate && $userTemplate->user_id == Yii::$app->user->id) {
                    $content = $userTemplate->fillTemplate($purchase);
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
}
