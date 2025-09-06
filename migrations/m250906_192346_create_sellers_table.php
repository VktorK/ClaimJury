<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sellers}}`.
 */
class m250906_192346_create_sellers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sellers}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Название продавца'),
            'address' => $this->text()->comment('Адрес'),
            'ogrn' => $this->string(15)->comment('ОГРН'),
            'date_creation' => $this->date()->comment('Дата создания'),
            'created_at' => $this->integer()->notNull()->comment('Дата добавления'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);
        
        $this->createIndex('idx-sellers-title', '{{%sellers}}', 'title');
        $this->createIndex('idx-sellers-ogrn', '{{%sellers}}', 'ogrn');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sellers}}');
    }
}
