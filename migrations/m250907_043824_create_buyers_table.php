<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%buyers}}`.
 */
class m250907_043824_create_buyers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%buyers}}', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(100)->notNull()->comment('Имя'),
            'lastName' => $this->string(100)->notNull()->comment('Фамилия'),
            'address' => $this->text()->comment('Адрес'),
            'birthday' => $this->date()->comment('Дата рождения'),
            'passport' => $this->string(20)->comment('Паспорт'),
            'image' => $this->string(255)->comment('Изображение'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        // Добавляем индексы для оптимизации поиска
        $this->createIndex('idx-buyers-firstName', '{{%buyers}}', 'firstName');
        $this->createIndex('idx-buyers-lastName', '{{%buyers}}', 'lastName');
        $this->createIndex('idx-buyers-passport', '{{%buyers}}', 'passport');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%buyers}}');
    }
}
