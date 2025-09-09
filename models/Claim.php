<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "claims".
 *
 * @property int $id
 * @property int $user_id
 * @property int $purchase_id
 * @property string $title
 * @property string|null $description
 * @property string $claim_type
 * @property string $status
 * @property int $claim_date
 * @property int|null $resolution_date
 * @property string|null $resolution_notes
 * @property float|null $amount_claimed
 * @property float|null $amount_resolved
 * @property string|null $tracking_number
 * @property int|null $document_sent_date
 * @property int|null $document_received_date
 * @property string|null $tracking_status
 * @property string|null $tracking_details
 * @property int|null $last_tracking_update
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Purchase $purchase
 */
class Claim extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CLOSED = 'closed';

    const TYPE_REPAIR = 'repair';
    const TYPE_REFUND = 'refund';
    const TYPE_REPLACEMENT = 'replacement';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%claims}}';
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
            [['user_id', 'purchase_id', 'claim_type', 'claim_date'], 'required'],
            [['user_id', 'purchase_id', 'claim_date', 'resolution_date', 'document_sent_date', 'document_received_date', 'last_tracking_update', 'created_at', 'updated_at'], 'integer'],
            [['description', 'resolution_notes', 'tracking_details'], 'string'],
            [['amount_claimed', 'amount_resolved'], 'number'],
            [['claim_type'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['tracking_number'], 'string', 'max' => 50],
            [['tracking_status'], 'string', 'max' => 100],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_RESOLVED, self::STATUS_REJECTED, self::STATUS_CLOSED]],
            [['claim_type'], 'in', 'range' => [self::TYPE_REPAIR, self::TYPE_REFUND, self::TYPE_REPLACEMENT]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::class, 'targetAttribute' => ['purchase_id' => 'id']],
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
            'purchase_id' => 'Покупка',
            'description' => 'Описание претензии',
            'claim_type' => 'Тип претензии',
            'status' => 'Статус',
            'claim_date' => 'Дата подачи претензии',
            'resolution_date' => 'Дата решения',
            'resolution_notes' => 'Примечания по решению',
            'amount_claimed' => 'Сумма претензии',
            'amount_resolved' => 'Сумма решения',
            'tracking_number' => 'Трек-номер',
            'document_sent_date' => 'Дата отправки документов',
            'document_received_date' => 'Дата получения документов',
            'tracking_status' => 'Статус отслеживания',
            'tracking_details' => 'Детали отслеживания',
            'last_tracking_update' => 'Последнее обновление отслеживания',
            'created_at' => 'Дата создания',
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
        return $this->hasOne(Purchase::class, ['id' => 'purchase_id']);
    }

    /**
     * Получить статус претензии на русском языке
     * @return string
     */
    public function getStatusLabel()
    {
        $statuses = [
            self::STATUS_PENDING => 'Ожидает рассмотрения',
            self::STATUS_IN_PROGRESS => 'В процессе',
            self::STATUS_RESOLVED => 'Решена',
            self::STATUS_REJECTED => 'Отклонена',
            self::STATUS_CLOSED => 'Закрыта',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Получить тип претензии на русском языке
     * @return string
     */
    public function getClaimTypeLabel()
    {
        $types = [
            self::TYPE_REPAIR => 'Ремонт',
            self::TYPE_REFUND => 'Возврат денежных средств',
            self::TYPE_REPLACEMENT => 'Замена товара на аналогичный товар',
        ];

        return $types[$this->claim_type] ?? $this->claim_type;
    }

    /**
     * Получить отформатированную дату подачи претензии
     * @return string
     */
    public function getFormattedClaimDate()
    {
        return date('d.m.Y', $this->claim_date);
    }

    /**
     * Получить отформатированную дату решения
     * @return string
     */
    public function getFormattedResolutionDate()
    {
        return $this->resolution_date ? date('d.m.Y', $this->resolution_date) : 'Не решена';
    }

    /**
     * Получить отформатированную дату создания
     * @return string
     */
    public function getFormattedCreatedDate()
    {
        return date('d.m.Y H:i', $this->created_at);
    }

    /**
     * Получить отформатированную сумму претензии
     * @return string
     */
    public function getFormattedAmountClaimed()
    {
        return $this->amount_claimed ? number_format($this->amount_claimed, 0, ',', ' ') . ' р' : 'Не указана';
    }

    /**
     * Получить отформатированную сумму решения
     * @return string
     */
    public function getFormattedAmountResolved()
    {
        return $this->amount_resolved ? number_format($this->amount_resolved, 0, ',', ' ') . ' р' : 'Не указана';
    }

    /**
     * Получить CSS класс для статуса
     * @return string
     */
    public function getStatusClass()
    {
        $classes = [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_IN_PROGRESS => 'badge-info',
            self::STATUS_RESOLVED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_CLOSED => 'badge-secondary',
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    /**
     * Проверить, можно ли редактировать претензию
     * @return bool
     */
    public function canEdit()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Проверить, можно ли удалить претензию
     * @return bool
     */
    public function canDelete()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Получить отформатированную дату отправки документов
     * @return string
     */
    public function getFormattedDocumentSentDate()
    {
        return $this->document_sent_date ? date('d.m.Y H:i', $this->document_sent_date) : 'Не отправлено';
    }

    /**
     * Получить отформатированную дату получения документов
     * @return string
     */
    public function getFormattedDocumentReceivedDate()
    {
        return $this->document_received_date ? date('d.m.Y H:i', $this->document_received_date) : 'Не получено';
    }

    /**
     * Получить отформатированную дату последнего обновления отслеживания
     * @return string
     */
    public function getFormattedLastTrackingUpdate()
    {
        return $this->last_tracking_update ? date('d.m.Y H:i', $this->last_tracking_update) : 'Никогда';
    }

    /**
     * Проверить, есть ли трек-номер
     * @return bool
     */
    public function hasTrackingNumber()
    {
        return !empty($this->tracking_number);
    }

    /**
     * Проверить, доставлены ли документы
     * @return bool
     */
    public function isDocumentDelivered()
    {
        return !empty($this->document_received_date);
    }

    /**
     * Получить детали отслеживания в виде массива
     * @return array
     */
    public function getTrackingDetailsArray()
    {
        if (empty($this->tracking_details)) {
            return [];
        }
        
        $details = json_decode($this->tracking_details, true);
        return is_array($details) ? $details : [];
    }

    /**
     * Установить детали отслеживания из массива
     * @param array $details
     */
    public function setTrackingDetailsArray($details)
    {
        $this->tracking_details = json_encode($details, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Обновить информацию об отслеживании
     * @param array $trackingData Данные от API Почты России
     * @return bool
     */
    public function updateTrackingInfo($trackingData)
    {
        if (empty($trackingData)) {
            return false;
        }

        $this->tracking_status = $trackingData['status'] ?? null;
        $this->setTrackingDetailsArray($trackingData);
        $this->last_tracking_update = time();

        // Если документы доставлены, обновляем дату получения
        if (isset($trackingData['delivered']) && $trackingData['delivered'] && !$this->document_received_date) {
            $this->document_received_date = time();
        }

        return $this->save(false);
    }

    /**
     * Получить CSS класс для статуса отслеживания
     * @return string
     */
    public function getTrackingStatusClass()
    {
        if (empty($this->tracking_status)) {
            return 'badge-secondary';
        }

        $statusMap = [
            'Принято в отделении связи' => 'badge-info',
            'В пути' => 'badge-warning',
            'Прибыло в место вручения' => 'badge-primary',
            'Вручено' => 'badge-success',
            'Возвращено отправителю' => 'badge-danger',
            'На таможне' => 'badge-secondary',
            'Утеряно' => 'badge-danger'
        ];

        return $statusMap[$this->tracking_status] ?? 'badge-secondary';
    }
}
