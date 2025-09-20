<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Product[] $products
 */
class Category extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%categories}}';
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
            [['title', 'slug'], 'required'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['slug'], 'unique'],
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
            'title' => 'Название',
            'slug' => 'URL-адрес',
            'description' => 'Описание',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * Получить количество товаров в категории
     */
    public function getProductsCount()
    {
        return $this->getProducts()->count();
    }

    /**
     * Получить отформатированную дату создания
     */
    public function getFormattedCreatedDate()
    {
        return date('d.m.Y H:i', $this->created_at);
    }

    /**
     * Получить текст статуса
     */
    public function getStatusText()
    {
        return $this->status == self::STATUS_ACTIVE ? 'Активна' : 'Неактивна';
    }

    /**
     * Получить активные категории
     */
    public static function getActiveCategories()
    {
        return static::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }
}