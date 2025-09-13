<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "claim_templates".
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property string $template_content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class ClaimTemplate extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'claim_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'template_content'], 'required'],
            [['template_content'], 'string'],
            [['status'], 'integer'],
            [['name', 'type', 'description'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название шаблона',
            'type' => 'Тип претензии',
            'description' => 'Описание',
            'template_content' => 'Содержание шаблона',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Получить статус шаблона
     */
    public function getStatusText()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Активен' : 'Неактивен';
    }

    /**
     * Получить список активных шаблонов по типу
     */
    public static function getActiveByType($type)
    {
        return self::find()
            ->where(['type' => $type, 'status' => self::STATUS_ACTIVE])
            ->all();
    }

    /**
     * Получить все типы претензий
     */
    public static function getClaimTypes()
    {
        return [
            'repair' => 'Ремонт',
            'replacement' => 'Замена товара',
            'refund' => 'Возврат денежных средств',
            'quality_issue' => 'Недостатки качества',
            'warranty' => 'Гарантийный случай',
            'delivery' => 'Проблемы с доставкой',
            'other' => 'Прочее',
        ];
    }

    /**
     * Получить название типа претензии
     */
    public function getTypeName()
    {
        $types = self::getClaimTypes();
        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    /**
     * Заполнить шаблон данными из покупки
     */
    public function fillTemplate($purchase)
    {
        $content = $this->template_content;
        
        // Данные продавца
        $seller = $purchase->seller;
        $content = str_replace('{SELLER_NAME}', $seller ? $seller->title : '', $content);
        $content = str_replace('{SELLER_INN}', '', $content); // Поле отсутствует
        $content = str_replace('{SELLER_OGRN}', $seller ? $seller->ogrn : '', $content);
        $content = str_replace('{SELLER_ADDRESS}', $seller ? $seller->address : '', $content);
        $content = str_replace('{SELLER_PHONE}', '', $content); // Поле отсутствует
        $content = str_replace('{SELLER_EMAIL}', '', $content); // Поле отсутствует
        
        // Данные покупателя
        $buyer = $purchase->buyer;
        $content = str_replace('{BUYER_FULL_NAME}', $buyer ? $buyer->getFullName() : '', $content);
        $content = str_replace('{BUYER_FIRST_NAME}', $buyer ? $buyer->firstName : '', $content);
        $content = str_replace('{BUYER_LAST_NAME}', $buyer ? $buyer->lastName : '', $content);
        $content = str_replace('{BUYER_MIDDLE_NAME}', $buyer ? $buyer->middleName : '', $content);
        $content = str_replace('{BUYER_ADDRESS}', $buyer ? $buyer->address : '', $content);
        $content = str_replace('{BUYER_PHONE}', '', $content); // Поле отсутствует
        $content = str_replace('{BUYER_EMAIL}', '', $content); // Поле отсутствует
        
        // Данные товара
        $product = $purchase->product;
        $content = str_replace('{PRODUCT_NAME}', $product ? $product->title : '', $content);
        $content = str_replace('{PRODUCT_MODEL}', $product ? $product->model : '', $content);
        $content = str_replace('{PRODUCT_SERIAL}', $product ? $product->serial_number : '', $content);
        $content = str_replace('{PRODUCT_CATEGORY}', $product && $product->category ? $product->category->title : '', $content);
        
        // Данные покупки
        $content = str_replace('{PURCHASE_DATE}', $purchase->purchase_date ? Yii::$app->formatter->asDate($purchase->purchase_date, 'php:d.m.Y') : '', $content);
        $content = str_replace('{PURCHASE_PRICE}', $purchase->amount ? Yii::$app->formatter->asCurrency($purchase->amount) : '', $content);
        $content = str_replace('{PURCHASE_WARRANTY}', $purchase->warranty_period ? $purchase->warranty_period . ' дней' : '', $content);
        
        // Информация о ремонте
        $content = str_replace('{WAS_REPAIRED_OFFICIALLY}', $purchase->getRepairStatusLabel(), $content);
        $content = str_replace('{REPAIR_DOCUMENT_DESCRIPTION}', $purchase->repair_document_description ?: '', $content);
        $content = str_replace('{REPAIR_DOCUMENT_DATE}', $purchase->repair_document_date ? Yii::$app->formatter->asDate($purchase->repair_document_date, 'php:d.m.Y') : '', $content);
        
        // Информация о доказательствах недостатка
        $content = str_replace('{DEFECT_PROOF_TYPE}', $purchase->getDefectProofTypeLabel(), $content);
        $content = str_replace('{DEFECT_PROOF_DOCUMENT_DESCRIPTION}', $purchase->defect_proof_document_description ?: '', $content);
        $content = str_replace('{DEFECT_PROOF_DOCUMENT_DATE}', $purchase->defect_proof_document_date ? Yii::$app->formatter->asDate($purchase->defect_proof_document_date, 'php:d.m.Y') : '', $content);
        
        // Различные типы описаний недостатков
        $content = str_replace('{REPAIR_DEFECT_DESCRIPTION}', $purchase->repair_defect_description ?: '', $content);
        $content = str_replace('{CURRENT_DEFECT_DESCRIPTION}', $purchase->current_defect_description ?: '', $content);
        $content = str_replace('{EXPERTISE_DEFECT_DESCRIPTION}', $purchase->expertise_defect_description ?: '', $content);
        $content = str_replace('{GENERAL_DEFECT_DESCRIPTION}', $purchase->general_defect_description ?: '', $content);
        
        // Обратная совместимость
        $content = str_replace('{DEFECT_DESCRIPTION}', $purchase->general_defect_description ?: '', $content);
        
        // Текущая дата
        $content = str_replace('{CURRENT_DATE}', Yii::$app->formatter->asDate(time(), 'php:d.m.Y'), $content);
        
        return $content;
    }
}
