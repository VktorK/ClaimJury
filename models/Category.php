<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Purchase[] $purchases
 */
class Category extends ActiveRecord
{
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
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название категории',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['category_id' => 'id']);
    }

    /**
     * Get categories dropdown
     *
     * @return array
     */
    public static function getCategoriesDropdown()
    {
        $categories = static::find()
            ->orderBy(['title' => SORT_ASC])
            ->all();
        
        $result = [];
        foreach ($categories as $category) {
            $result[$category->id] = $category->title;
        }
        
        return $result;
    }

    /**
     * Get formatted created date
     *
     * @return string
     */
    public function getFormattedCreatedDate()
    {
        if (!$this->created_at) {
            return 'Не указана';
        }
        
        $date = new \DateTime();
        $date->setTimestamp($this->created_at);
        
        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
        
        $day = $date->format('d');
        $month = $months[(int)$date->format('n')];
        $year = $date->format('Y');
        
        return $day . ' ' . $month . ' ' . $year . ' года';
    }
}
