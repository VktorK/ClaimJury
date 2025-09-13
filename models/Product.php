<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $category_id
 * @property string|null $image
 * @property string|null $serial_number
 * @property string|null $model
 * @property int|null $warranty_period
 * @property int|null $purchases_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category|null $category
 * @property Purchase|null $purchase
 * @property Purchase[] $purchases
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
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
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // Удаляем изображение товара при удалении товара
            if ($this->image) {
                $filePath = Yii::getAlias('@webroot/uploads/products/') . $this->image;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
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
            [['title', 'category_id'], 'required'],
            [['description'], 'string'],
            [['category_id', 'warranty_period', 'purchases_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'serial_number', 'model'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 500],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg, gif', 'maxSize' => 5 * 1024 * 1024],
            [['warranty_period'], 'integer', 'min' => 0],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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
            'title' => 'Название товара',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'image' => 'Изображение',
            'serial_number' => 'Серийный номер',
            'model' => 'Модель',
            'warranty_period' => 'Гарантийный срок (месяцы)',
            'purchases_id' => 'Покупка',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
        return $this->hasMany(Purchase::class, ['product_id' => 'id']);
    }

    /**
     * Upload product image
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function uploadImage($file)
    {
        if ($file) {
            $uploadPath = Yii::getAlias('@webroot/uploads/products/');
            FileHelper::createDirectory($uploadPath);
            
            $fileName = $this->id . '_' . time() . '.' . $file->extension;
            $filePath = $uploadPath . $fileName;
            
            if ($file->saveAs($filePath)) {
                // Удаляем старое изображение если есть
                if ($this->image) {
                    $oldFilePath = $uploadPath . $this->image;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                $this->image = $fileName;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get product image URL
     *
     * @return string
     */
    public function getImageUrl()
    {
        if ($this->image) {
            $filePath = Yii::getAlias('@webroot/uploads/products/') . $this->image;
            if (file_exists($filePath)) {
                return Yii::getAlias('@web/uploads/products/') . $this->image;
            } else {
                // Логируем ошибку для отладки
                Yii::error("Product image file not found: " . $filePath, __METHOD__);
            }
        }
        
        return Yii::getAlias('@web/images/no-product.svg');
    }

    /**
     * Get products dropdown
     *
     * @return array
     */
    public static function getProductsDropdown()
    {
        $products = static::find()
            ->orderBy(['title' => SORT_ASC])
            ->all();
        
        $result = [];
        foreach ($products as $product) {
            $result[$product->id] = $product->title;
        }
        
        return $result;
    }

    /**
     * Get products by category
     *
     * @param int $categoryId
     * @return array
     */
    public static function getProductsByCategory($categoryId)
    {
        return static::find()
            ->where(['category_id' => $categoryId])
            ->orderBy(['title' => SORT_ASC])
            ->all();
    }

    /**
     * Check if product category is "Бытовая техника"
     *
     * @return bool
     */
    public function isHomeAppliance()
    {
        return $this->category && 
               (stripos($this->category->title, 'бытовая техника') !== false ||
                stripos($this->category->title, 'техника') !== false);
    }

    /**
     * Get formatted warranty period
     *
     * @return string
     */
    public function getFormattedWarrantyPeriod()
    {
        if (!$this->warranty_period) {
            return 'Не указан';
        }

        $months = $this->warranty_period;
        $years = floor($months / 12);
        $remainingMonths = $months % 12;

        $result = [];
        if ($years > 0) {
            $result[] = $years . ' ' . $this->getYearWord($years);
        }
        if ($remainingMonths > 0) {
            $result[] = $remainingMonths . ' ' . $this->getMonthWord($remainingMonths);
        }

        return implode(', ', $result);
    }

    /**
     * Get year word with correct ending
     */
    private function getYearWord($count)
    {
        $lastDigit = $count % 10;
        $lastTwoDigits = $count % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return 'лет';
        } elseif ($lastDigit == 1) {
            return 'год';
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            return 'года';
        } else {
            return 'лет';
        }
    }

    /**
     * Get month word with correct ending
     */
    private function getMonthWord($count)
    {
        $lastDigit = $count % 10;
        $lastTwoDigits = $count % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
            return 'месяцев';
        } elseif ($lastDigit == 1) {
            return 'месяц';
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            return 'месяца';
        } else {
            return 'месяцев';
        }
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
