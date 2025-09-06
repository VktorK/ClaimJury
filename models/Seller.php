<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sellers".
 *
 * @property int $id
 * @property string $title
 * @property string|null $address
 * @property string|null $ogrn
 * @property string|null $date_creation
 * @property int $user_id
 * @property int|null $purchases_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Purchase|null $purchase
 * @property Purchase[] $purchases
 */
class Seller extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sellers}}';
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
            [['title', 'user_id'], 'required'],
            [['address'], 'string'],
            [['user_id', 'purchases_id', 'created_at', 'updated_at'], 'integer'],
            [['date_creation'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['ogrn'], 'string', 'max' => 15],
            [['ogrn'], 'match', 'pattern' => '/^\d{13}$/', 'message' => 'ОГРН должен содержать 13 цифр'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['purchases_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::class, 'targetAttribute' => ['purchases_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название продавца',
            'address' => 'Адрес',
            'ogrn' => 'ОГРН',
            'date_creation' => 'Дата создания',
            'user_id' => 'Пользователь',
            'purchases_id' => 'Покупка',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
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
     * Gets query for [[Purchase]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::class, ['id' => 'purchases_id']);
    }

    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['seller_id' => 'id']);
    }

    /**
     * Получить список продавцов для пользователя
     * @param int $userId ID пользователя
     * @return array
     */
    public static function getSellersForUser($userId)
    {
        return static::find()
            ->where(['user_id' => $userId])
            ->orderBy(['title' => SORT_ASC])
            ->all();
    }

    /**
     * Получить массив для dropdown
     * @param int $userId ID пользователя
     * @return array
     */
    public static function getSellersDropdown($userId)
    {
        $sellers = static::getSellersForUser($userId);
        $result = [];
        
        foreach ($sellers as $seller) {
            $result[$seller->id] = $seller->title;
        }
        
        return $result;
    }

    /**
     * Получить полное название с адресом
     * @return string
     */
    public function getFullTitle()
    {
        $result = $this->title;
        if ($this->address) {
            $result .= ' (' . $this->address . ')';
        }
        return $result;
    }

    /**
     * Get formatted date creation
     *
     * @return string
     */
    public function getFormattedDateCreation()
    {
        if (!$this->date_creation) {
            return 'Не указана';
        }
        
        $date = new \DateTime($this->date_creation);
        
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
