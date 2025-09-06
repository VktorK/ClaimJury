<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m250906_194653_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Название товара'),
            'description' => $this->text()->comment('Описание товара'),
            'category_id' => $this->integer()->comment('ID категории'),
            'image' => $this->string(500)->comment('Путь к изображению товара'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);
        
        $this->createIndex('idx-products-category_id', '{{%products}}', 'category_id');
        $this->createIndex('idx-products-title', '{{%products}}', 'title');
        $this->addForeignKey(
            'fk-products-category_id',
            '{{%products}}',
            'category_id',
            '{{%categories}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products-category_id', '{{%products}}');
        $this->dropIndex('idx-products-category_id', '{{%products}}');
        $this->dropIndex('idx-products-title', '{{%products}}');
        $this->dropTable('{{%products}}');
    }
}
