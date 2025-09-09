<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property int $user_id
 * @property string $product_name
 * @property string $seller_name
 * @property int|null $seller_id
 * @property int|null $product_id
 * @property int|null $buyer_id
 * @property string $purchase_date
 * @property float $amount
 * @property string $currency
 * @property string $description
 * @property string $receipt_image
 * @property int|null $warranty_period
 * @property string|null $appeal_deadline
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Seller|null $seller
 * @property Product|null $product
 * @property Buyer|null $buyer
 */
class Purchase extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchases';
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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Автоматически заполняем seller_name на основе выбранного продавца
            if ($this->seller_id && $this->seller) {
                $this->seller_name = $this->seller->title;
            }
            
            // Автоматически заполняем product_name на основе выбранного товара
            if ($this->product_id && $this->product) {
                $this->product_name = $this->product->title;
            }
            
            // Автоматически рассчитываем срок обращения (дата покупки + 2 года)
            if ($this->purchase_date) {
                $purchaseDate = new \DateTime($this->purchase_date);
                $purchaseDate->add(new \DateInterval('P2Y')); // Добавляем 2 года
                $this->appeal_deadline = $purchaseDate->format('Y-m-d');
            }
            
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // Каскадное удаление товара при удалении покупки
            // Продавец и категория остаются, так как носят информационный характер
            if ($this->product_id && $this->product) {
                $this->product->delete();
            }
            
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'purchase_date', 'amount'], 'required'],
            [['user_id', 'seller_id', 'product_id', 'buyer_id', 'warranty_period', 'created_at', 'updated_at'], 'integer'],
            [['purchase_date', 'appeal_deadline'], 'safe'],
            [['amount'], 'number', 'min' => 0],
            [['product_name', 'seller_name'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 3],
            [['description'], 'string', 'max' => 1000],
            [['receipt_image'], 'string', 'max' => 500],
            [['receipt_image'], 'file', 'extensions' => 'png, jpg, jpeg, gif, pdf', 'maxSize' => 5 * 1024 * 1024],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::class, 'targetAttribute' => ['seller_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Buyer::class, 'targetAttribute' => ['buyer_id' => 'id']],
            [['seller_id'], 'required', 'message' => 'Необходимо выбрать продавца'],
            [['buyer_id'], 'required', 'message' => 'Необходимо выбрать покупателя'],
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
            'product_name' => 'Название товара',
            'seller_name' => 'Продавец (текст)',
            'seller_id' => 'Продавец',
            'product_id' => 'Товар',
            'buyer_id' => 'Покупатель',
            'purchase_date' => 'Дата покупки',
            'amount' => 'Сумма',
            'currency' => 'Валюта',
            'description' => 'Описание',
            'receipt_image' => 'Чек',
            'warranty_period' => 'Гарантийный срок (дни)',
            'appeal_deadline' => 'Срок обращения',
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
     * Gets query for [[Seller]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::class, ['id' => 'seller_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Buyer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(Buyer::class, ['id' => 'buyer_id']);
    }

    /**
     * Gets query for [[Claims]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClaims()
    {
        return $this->hasMany(Claim::class, ['purchase_id' => 'id']);
    }

    /**
     * Upload receipt image
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function uploadReceipt($file)
    {
        if ($file) {
            $uploadPath = Yii::getAlias('@webroot/uploads/receipts/');
            FileHelper::createDirectory($uploadPath);
            
            $fileName = $this->user_id . '_' . time() . '.' . $file->extension;
            $filePath = $uploadPath . $fileName;
            
            if ($file->saveAs($filePath)) {
                // Удаляем старый файл если есть
                if ($this->receipt_image) {
                    $oldFilePath = $uploadPath . $this->receipt_image;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                $this->receipt_image = $fileName;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get receipt image URL
     *
     * @return string
     */
    public function getReceiptUrl()
    {
        if ($this->receipt_image) {
            $filePath = Yii::getAlias('@webroot/uploads/receipts/') . $this->receipt_image;
            if (file_exists($filePath)) {
                return Yii::getAlias('@web/uploads/receipts/') . $this->receipt_image;
            } else {
                // Логируем ошибку для отладки
                Yii::error("Receipt file not found: " . $filePath, __METHOD__);
            }
        }
        
        return Yii::getAlias('@web/images/no-receipt.png');
    }


    /**
     * Get formatted amount with currency
     *
     * @return string
     */
    public function getFormattedAmount()
    {
        $amount = $this->amount;
        $rubles = floor($amount);
        $kopecks = round(($amount - $rubles) * 100);
        
        return $rubles . ' р ' . sprintf('%02d', $kopecks) . ' коп.';
    }

    /**
     * Get formatted purchase date
     *
     * @return string
     */
    public function getFormattedPurchaseDate()
    {
        if (!$this->purchase_date) {
            return 'Не указана';
        }
        
        $date = new \DateTime($this->purchase_date);
        
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
     * Get formatted appeal deadline
     *
     * @return string
     */
    public function getFormattedAppealDeadline()
    {
        if (!$this->appeal_deadline) {
            return 'Не рассчитан';
        }
        
        $date = new \DateTime($this->appeal_deadline);
        
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
     * Get formatted warranty period
     *
     * @return string
     */
    public function getFormattedWarrantyPeriod()
    {
        // Если есть связанный товар, берем гарантийный срок из него
        if ($this->product_id && $this->product) {
            return $this->product->getFormattedWarrantyPeriod();
        }
        
        // Иначе показываем "Не указан"
        return 'Не указан';
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
     * Get currency options
     *
     * @return array
     */
    public static function getCurrencyOptions()
    {
        return [
            'RUB' => 'Российский рубль (RUB)',
            'USD' => 'Доллар США (USD)',
            'EUR' => 'Евро (EUR)',
            'KZT' => 'Казахстанский тенге (KZT)',
            'BYN' => 'Белорусский рубль (BYN)',
        ];
    }

    /**
     * Get total amount for user
     *
     * @param int $userId
     * @return float
     */
    public static function getTotalAmountForUser($userId)
    {
        return static::find()
            ->where(['user_id' => $userId])
            ->sum('amount') ?: 0;
    }

    /**
     * Get purchases count for user
     *
     * @param int $userId
     * @return int
     */
    public static function getPurchasesCountForUser($userId)
    {
        return static::find()
            ->where(['user_id' => $userId])
            ->count();
    }

    /**
     * Get seller name (from relation)
     *
     * @return string
     */
    public function getSellerName()
    {
        if ($this->seller) {
            return $this->seller->title;
        }
        return 'Не указан';
    }

    /**
     * Get sellers dropdown for user
     *
     * @param int $userId
     * @return array
     */
    public static function getSellersDropdown($userId)
    {
        return Seller::getSellersDropdown($userId);
    }

    /**
     * Get products dropdown
     *
     * @return array
     */
    public static function getProductsDropdown()
    {
        return Product::getProductsDropdown();
    }

    /**
     * Get buyers dropdown
     *
     * @return array
     */
    public static function getBuyersDropdown()
    {
        return Buyer::getBuyersDropdown();
    }

    /**
     * Get product name (from relation)
     *
     * @return string
     */
    public function getProductName()
    {
        if ($this->product) {
            return $this->product->title;
        }
        return $this->product_name ?: 'Не указан';
    }

    /**
     * Get buyer name (from relation)
     *
     * @return string
     */
    public function getBuyerName()
    {
        if ($this->buyer) {
            return $this->buyer->getFullName();
        }
        return 'Не указан';
    }
}
