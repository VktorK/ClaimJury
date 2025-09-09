<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "buyers".
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $middleName
 * @property string|null $address
 * @property string|null $birthday
 * @property string|null $passport
 * @property string|null $image
 * @property int $created_at
 * @property int $updated_at
 */
class Buyer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%buyers}}';
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
            [['firstName', 'lastName', 'middleName'], 'required'],
            [['firstName', 'lastName', 'middleName'], 'string', 'max' => 100],
            [['address'], 'string'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            [['passport'], 'string', 'max' => 20],
            [['image'], 'string', 'max' => 255],
            [['firstName', 'lastName', 'middleName'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'Имя',
            'lastName' => 'Фамилия',
            'middleName' => 'Отчество',
            'address' => 'Адрес',
            'birthday' => 'Дата рождения',
            'passport' => 'Паспорт',
            'image' => 'Фотография паспорта',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        $parts = array_filter([$this->lastName, $this->firstName, $this->middleName]);
        return trim(implode(' ', $parts));
    }

    /**
     * Get formatted birthday
     *
     * @return string
     */
    public function getFormattedBirthday()
    {
        if (!$this->birthday) {
            return 'Не указана';
        }
        
        $date = new \DateTime($this->birthday);
        
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

    /**
     * Get image URL
     *
     * @return string
     */
    public function getImageUrl()
    {
        if ($this->image && file_exists(Yii::getAlias('@webroot/uploads/buyers/') . $this->image)) {
            return Yii::getAlias('@web/uploads/buyers/') . $this->image;
        }
        return Yii::getAlias('@web/images/no-buyer.svg');
    }

    /**
     * Upload image
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function uploadImage($file)
    {
        if ($file) {
            $uploadPath = Yii::getAlias('@webroot/uploads/buyers/');
            FileHelper::createDirectory($uploadPath);
            
            $fileName = $this->id . '_' . time() . '.' . $file->extension;
            $filePath = $uploadPath . $fileName;
            
            if ($file->saveAs($filePath)) {
                // Удаляем старое изображение
                if ($this->image && file_exists($uploadPath . $this->image)) {
                    unlink($uploadPath . $this->image);
                }
                
                $this->image = $fileName;
                return $this->save(false);
            }
        }
        return false;
    }

    /**
     * Get buyers dropdown
     *
     * @return array
     */
    public static function getBuyersDropdown()
    {
        $buyers = static::find()
            ->orderBy(['lastName' => SORT_ASC, 'firstName' => SORT_ASC, 'middleName' => SORT_ASC])
            ->all();
        
        $result = [];
        foreach ($buyers as $buyer) {
            $result[$buyer->id] = $buyer->getFullName();
        }
        
        return $result;
    }

    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['buyer_id' => 'id']);
    }

    /**
     * Before delete
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->image) {
                $filePath = Yii::getAlias('@webroot/uploads/buyers/') . $this->image;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            return true;
        }
        return false;
    }
}
