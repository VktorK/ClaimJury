<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "packages".
 *
 * @property int $id
 * @property string $track_number Номер отслеживания
 * @property int $status Статус отправления
 * @property int|null $last_check Время последней проверки
 * @property string|null $data Дополнительные данные в JSON
 * @property int|null $claim_id ID претензии
 * @property int $created_at Время создания
 * @property int $updated_at Время обновления
 * 
 * @property Claim $claim
 */
class Package extends ActiveRecord
{
    // Константы статусов
    const STATUS_PENDING = 0;        // Ожидает
    const STATUS_ACCEPTED = 1;       // Принято
    const STATUS_IN_TRANSIT = 2;     // В пути
    const STATUS_OUT_FOR_DELIVERY = 3; // Доставляется
    const STATUS_DELIVERED = 4;      // Доставлено
    const STATUS_EXCEPTION = 5;      // Проблема с доставкой
    const STATUS_RETURNED = 6;       // Возвращено
    const STATUS_CANCELLED = 7;      // Отменено

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages';
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
            [['track_number'], 'required'],
            [['status', 'last_check', 'claim_id', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string'],
            [['track_number'], 'string', 'max' => 50],
            [['track_number'], 'unique'],
            [['claim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Claim::class, 'targetAttribute' => ['claim_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_number' => 'Номер отслеживания',
            'status' => 'Статус отправления',
            'last_check' => 'Время последней проверки',
            'data' => 'Дополнительные данные',
            'claim_id' => 'ID претензии',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * Получить массив всех статусов
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Ожидает',
            self::STATUS_ACCEPTED => 'Принято',
            self::STATUS_IN_TRANSIT => 'В пути',
            self::STATUS_OUT_FOR_DELIVERY => 'Доставляется',
            self::STATUS_DELIVERED => 'Доставлено',
            self::STATUS_EXCEPTION => 'Проблема с доставкой',
            self::STATUS_RETURNED => 'Возвращено',
            self::STATUS_CANCELLED => 'Отменено',
        ];
    }

    /**
     * Получить название статуса
     * @return string
     */
    public function getStatusLabel()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Неизвестный статус';
    }

    /**
     * Получить CSS класс для статуса
     * @return string
     */
    public function getStatusClass()
    {
        $classes = [
            self::STATUS_PENDING => 'badge-secondary',
            self::STATUS_ACCEPTED => 'badge-info',
            self::STATUS_IN_TRANSIT => 'badge-primary',
            self::STATUS_OUT_FOR_DELIVERY => 'badge-warning',
            self::STATUS_DELIVERED => 'badge-success',
            self::STATUS_EXCEPTION => 'badge-danger',
            self::STATUS_RETURNED => 'badge-warning',
            self::STATUS_CANCELLED => 'badge-dark',
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    /**
     * Проверить, доставлено ли отправление
     * @return bool
     */
    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Проверить, есть ли проблемы с доставкой
     * @return bool
     */
    public function hasException()
    {
        return $this->status === self::STATUS_EXCEPTION;
    }

    /**
     * Получить данные в виде массива
     * @return array|null
     */
    public function getDataArray()
    {
        if (empty($this->data)) {
            return null;
        }

        return json_decode($this->data, true);
    }

    /**
     * Установить данные из массива
     * @param array $data
     */
    public function setDataArray($data)
    {
        $this->data = json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Обновить время последней проверки
     */
    public function updateLastCheck()
    {
        $this->last_check = time();
        $this->save(false);
    }

    /**
     * Найти пакет по номеру отслеживания
     * @param string $trackNumber
     * @return static|null
     */
    public static function findByTrackNumber($trackNumber)
    {
        return static::findOne(['track_number' => $trackNumber]);
    }

    /**
     * Создать или обновить пакет
     * @param string $trackNumber
     * @param int $status
     * @param array|null $data
     * @param int|null $claimId
     * @return static
     */
    public static function createOrUpdate(string $trackNumber, int $status = self::STATUS_PENDING, array $data = null, int $claimId = null): ?Package
    {
        $package = static::findByTrackNumber($trackNumber);
        
        if (!$package) {
            $package = new static();
            $package->track_number = $trackNumber;
        }

        $package->status = $status;
        $package->last_check = time();
        
        if ($data !== null) {
            $package->setDataArray($data);
        }

        if ($claimId !== null) {
            $package->claim_id = $claimId;
        }

        $package->save();

        return $package;
    }

    /**
     * Получить отформатированное время последней проверки
     * @return string
     */
    public function getFormattedLastCheck()
    {
        if (!$this->last_check) {
            return 'Никогда';
        }

        return Yii::$app->formatter->asDatetime($this->last_check, 'php:d.m.Y H:i');
    }

    /**
     * Получить отформатированное время создания
     * @return string
     */
    public function getFormattedCreatedAt()
    {
        return Yii::$app->formatter->asDatetime($this->created_at, 'php:d.m.Y H:i');
    }

    /**
     * Получить отформатированное время обновления
     * @return string
     */
    public function getFormattedUpdatedAt()
    {
        return Yii::$app->formatter->asDatetime($this->updated_at, 'php:d.m.Y H:i');
    }

    /**
     * Связь с претензией
     * @return \yii\db\ActiveQuery
     */
    public function getClaim()
    {
        return $this->hasOne(Claim::class, ['id' => 'claim_id']);
    }
}
