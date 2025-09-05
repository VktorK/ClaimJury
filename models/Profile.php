<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "profiles".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $phone
 * @property string|null $birth_date
 * @property string|null $gender
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $avatar
 * @property string|null $bio
 * @property string|null $website
 * @property string|null $linkedin
 * @property string|null $twitter
 * @property string|null $facebook
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Profile extends ActiveRecord
{
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_OTHER = 'other';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profiles}}';
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
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['birth_date'], 'safe'],
            [['address', 'bio'], 'string'],
            [['first_name', 'last_name', 'middle_name', 'phone', 'gender', 'city', 'country', 'postal_code', 'avatar', 'website', 'linkedin', 'twitter', 'facebook'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern' => '/^[\+]?[0-9\s\-\(\)]+$/', 'message' => 'Некорректный формат телефона'],
            [['website', 'linkedin', 'twitter', 'facebook'], 'url', 'message' => 'Введите корректный URL'],
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE, self::GENDER_OTHER]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'phone' => 'Телефон',
            'birth_date' => 'Дата рождения',
            'gender' => 'Пол',
            'address' => 'Адрес',
            'city' => 'Город',
            'country' => 'Страна',
            'postal_code' => 'Почтовый индекс',
            'avatar' => 'Аватар',
            'bio' => 'О себе',
            'website' => 'Веб-сайт',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter',
            'facebook' => 'Facebook',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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
     * Получить полное имя пользователя
     * @return string
     */
    public function getFullName()
    {
        $parts = array_filter([$this->last_name, $this->first_name, $this->middle_name]);
        return implode(' ', $parts) ?: $this->user->username;
    }

    /**
     * Получить инициалы пользователя
     * @return string
     */
    public function getInitials()
    {
        $initials = '';
        if ($this->first_name) {
            $initials .= mb_substr($this->first_name, 0, 1, 'UTF-8');
        }
        if ($this->last_name) {
            $initials .= mb_substr($this->last_name, 0, 1, 'UTF-8');
        }
        return mb_strtoupper($initials, 'UTF-8') ?: mb_strtoupper(mb_substr($this->user->username, 0, 2, 'UTF-8'), 'UTF-8');
    }

    /**
     * Получить возраст пользователя
     * @return int|null
     */
    public function getAge()
    {
        if (!$this->birth_date) {
            return null;
        }
        
        $birthDate = new \DateTime($this->birth_date);
        $today = new \DateTime();
        $age = $today->diff($birthDate);
        
        return $age->y;
    }

    /**
     * Получить список полов
     * @return array
     */
    public static function getGenderOptions()
    {
        return [
            self::GENDER_MALE => 'Мужской',
            self::GENDER_FEMALE => 'Женский',
            self::GENDER_OTHER => 'Другой',
        ];
    }

    /**
     * Получить URL аватара
     * @return string
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return Yii::getAlias('@web/uploads/avatars/') . $this->avatar;
        }
        return Yii::getAlias('@web/images/default-avatar.png');
    }

    /**
     * Загрузить аватар
     * @param UploadedFile $file
     * @return bool
     */
    public function uploadAvatar(UploadedFile $file)
    {
        $uploadPath = Yii::getAlias('@webroot/uploads/avatars/');
        
        // Создаем директорию если не существует
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = $this->user_id . '_' . time() . '.' . $file->extension;
        $filePath = $uploadPath . $fileName;

        if ($file->saveAs($filePath)) {
            // Удаляем старый аватар если есть
            if ($this->avatar && file_exists($uploadPath . $this->avatar)) {
                unlink($uploadPath . $this->avatar);
            }
            
            $this->avatar = $fileName;
            return $this->save(false);
        }

        return false;
    }
}
