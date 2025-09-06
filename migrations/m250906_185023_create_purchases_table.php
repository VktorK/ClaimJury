<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchases}}`.
 */
class m250906_185023_create_purchases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchases}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
            'product_name' => $this->string(255)->notNull()->comment('Название товара'),
            'seller_name' => $this->string(255)->notNull()->comment('Название продавца'),
            'purchase_date' => $this->date()->notNull()->comment('Дата покупки'),
            'amount' => $this->decimal(10, 2)->notNull()->comment('Сумма покупки'),
            'currency' => $this->string(3)->defaultValue('RUB')->comment('Валюта'),
            'description' => $this->text()->comment('Описание покупки'),
            'receipt_image' => $this->string(500)->comment('Путь к изображению чека'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        // Добавляем индексы
        $this->createIndex('idx-purchases-user_id', '{{%purchases}}', 'user_id');
        $this->createIndex('idx-purchases-purchase_date', '{{%purchases}}', 'purchase_date');
        $this->createIndex('idx-purchases-amount', '{{%purchases}}', 'amount');

        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk-purchases-user_id',
            '{{%purchases}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%purchases}}');
    }
}
