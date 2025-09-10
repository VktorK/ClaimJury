<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "articles".
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property string $image
 * @property int $category_id
 * @property int $status
 * @property int $views
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 */
class Article extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
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
            [['title', 'slug', 'content', 'category_id'], 'required'],
            [['content'], 'string'],
            [['category_id', 'status', 'views'], 'integer'],
            [['title', 'slug', 'excerpt', 'image'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['views'], 'default', 'value' => 0],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'URL-адрес',
            'excerpt' => 'Краткое описание',
            'content' => 'Содержание',
            'image' => 'Изображение',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'views' => 'Просмотры',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Получить статус статьи
     */
    public function getStatusText()
    {
        return $this->status == self::STATUS_PUBLISHED ? 'Опубликована' : 'Черновик';
    }

    /**
     * Получить URL статьи
     */
    public function getUrl()
    {
        return Url::to(['/blog/view', 'slug' => $this->slug]);
    }

    /**
     * Получить отформатированную дату
     */
    public function getFormattedDate()
    {
        return Yii::$app->formatter->asDate($this->created_at, 'php:d.m.Y');
    }

    /**
     * Получить краткое описание
     */
    public function getShortExcerpt($length = 150)
    {
        if ($this->excerpt) {
            return mb_substr(strip_tags($this->excerpt), 0, $length) . '...';
        }
        return mb_substr(strip_tags($this->content), 0, $length) . '...';
    }

    /**
     * Увеличить количество просмотров
     */
    public function incrementViews()
    {
        $this->views++;
        $this->save(false);
    }
}
