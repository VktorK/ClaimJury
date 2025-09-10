<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_claim_templates".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $original_template_id
 * @property string $name
 * @property string $type
 * @property string|null $description
 * @property string $template_content
 * @property bool $is_favorite
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property ClaimTemplate $originalTemplate
 */
class UserClaimTemplate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_claim_templates}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'type', 'template_content'], 'required'],
            [['user_id', 'original_template_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['template_content'], 'string'],
            [['is_favorite'], 'boolean'],
            [['name', 'type'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['original_template_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClaimTemplate::class, 'targetAttribute' => ['original_template_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'original_template_id' => 'Оригинальный шаблон',
            'name' => 'Название',
            'type' => 'Тип',
            'description' => 'Описание',
            'template_content' => 'Содержание',
            'is_favorite' => 'Избранный',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[OriginalTemplate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOriginalTemplate()
    {
        return $this->hasOne(ClaimTemplate::class, ['id' => 'original_template_id']);
    }

    /**
     * Получить активные шаблоны пользователя по типу
     * @param int $userId
     * @param string $type
     * @return array
     */
    public static function getActiveTemplatesByType($userId, $type)
    {
        return static::find()
            ->where(['user_id' => $userId, 'type' => $type, 'status' => 1])
            ->orderBy(['is_favorite' => SORT_DESC, 'created_at' => SORT_DESC])
            ->all();
    }

    /**
     * Получить все активные шаблоны пользователя
     * @param int $userId
     * @return array
     */
    public static function getAllActiveTemplates($userId)
    {
        return static::find()
            ->where(['user_id' => $userId, 'status' => 1])
            ->orderBy(['is_favorite' => SORT_DESC, 'created_at' => SORT_DESC])
            ->all();
    }

    /**
     * Заполнить шаблон данными покупки
     * @param Purchase $purchase
     * @return string
     */
    public function fillTemplate($purchase)
    {
        $content = $this->template_content;
        
        // Данные продавца
        $seller = $purchase->seller;
        $content = str_replace('{SELLER_NAME}', $seller ? $seller->title : '', $content);
        $content = str_replace('{SELLER_INN}', '', $content);
        $content = str_replace('{SELLER_OGRN}', $seller ? $seller->ogrn : '', $content);
        $content = str_replace('{SELLER_ADDRESS}', $seller ? $seller->address : '', $content);
        $content = str_replace('{SELLER_PHONE}', '', $content);
        $content = str_replace('{SELLER_EMAIL}', '', $content);
        
        // Данные покупателя
        $buyer = $purchase->buyer;
        $content = str_replace('{BUYER_FULL_NAME}', $buyer ? $buyer->getFullName() : '', $content);
        $content = str_replace('{BUYER_FIRST_NAME}', $buyer ? $buyer->firstName : '', $content);
        $content = str_replace('{BUYER_LAST_NAME}', $buyer ? $buyer->lastName : '', $content);
        $content = str_replace('{BUYER_MIDDLE_NAME}', $buyer ? $buyer->middleName : '', $content);
        $content = str_replace('{BUYER_ADDRESS}', $buyer ? $buyer->address : '', $content);
        $content = str_replace('{BUYER_PHONE}', '', $content);
        $content = str_replace('{BUYER_EMAIL}', '', $content);
        
        // Данные товара
        $product = $purchase->product;
        $content = str_replace('{PRODUCT_NAME}', $product ? $product->title : '', $content);
        $content = str_replace('{PRODUCT_MODEL}', '', $content);
        $content = str_replace('{PRODUCT_SERIAL}', $product ? $product->serial_number : '', $content);
        $content = str_replace('{PRODUCT_CATEGORY}', $product && $product->category ? $product->category->name : '', $content);
        
        // Данные покупки
        $content = str_replace('{PURCHASE_DATE}', $purchase->purchase_date ? Yii::$app->formatter->asDate($purchase->purchase_date, 'php:d.m.Y') : '', $content);
        $content = str_replace('{PURCHASE_PRICE}', $purchase->amount ? Yii::$app->formatter->asCurrency($purchase->amount) : '', $content);
        $content = str_replace('{PURCHASE_WARRANTY}', $purchase->warranty_period ? $purchase->warranty_period . ' дней' : '', $content);
        
        // Текущая дата
        $content = str_replace('{CURRENT_DATE}', Yii::$app->formatter->asDate(time(), 'php:d.m.Y'), $content);
        
        return $content;
    }

    /**
     * Создать пользовательский шаблон на основе оригинального
     * @param int $userId
     * @param int $originalTemplateId
     * @param string $name
     * @param string $content
     * @return static|null
     */
    public static function createFromOriginal($userId, $originalTemplateId, $name, $content)
    {
        $originalTemplate = ClaimTemplate::findOne($originalTemplateId);
        if (!$originalTemplate) {
            return null;
        }

        $userTemplate = new static();
        $userTemplate->user_id = $userId;
        $userTemplate->original_template_id = $originalTemplateId;
        $userTemplate->name = $name;
        $userTemplate->type = $originalTemplate->type;
        $userTemplate->description = $originalTemplate->description;
        $userTemplate->template_content = $content;
        $userTemplate->is_favorite = false;
        $userTemplate->status = 1;

        if ($userTemplate->save()) {
            return $userTemplate;
        }

        return null;
    }

    /**
     * Создать полностью пользовательский шаблон
     * @param int $userId
     * @param string $name
     * @param string $type
     * @param string $content
     * @param string|null $description
     * @return static|null
     */
    public static function createCustom($userId, $name, $type, $content, $description = null)
    {
        $userTemplate = new static();
        $userTemplate->user_id = $userId;
        $userTemplate->original_template_id = null;
        $userTemplate->name = $name;
        $userTemplate->type = $type;
        $userTemplate->description = $description;
        $userTemplate->template_content = $content;
        $userTemplate->is_favorite = false;
        $userTemplate->status = 1;

        if ($userTemplate->save()) {
            return $userTemplate;
        }

        return null;
    }
}
