<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%packages}}`.
 */
class m250911_161913_create_packages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%packages}}', [
            'id' => $this->primaryKey(),
            'track_number' => $this->string(50)->notNull()->comment('Номер отслеживания'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус отправления'),
            'last_check' => $this->integer()->null()->comment('Время последней проверки'),
            'data' => $this->text()->null()->comment('Дополнительные данные в JSON'),
            'created_at' => $this->integer()->notNull()->comment('Время создания'),
            'updated_at' => $this->integer()->notNull()->comment('Время обновления'),
        ]);

        // Добавляем индексы для оптимизации
        $this->createIndex('idx_packages_track_number', '{{%packages}}', 'track_number');
        $this->createIndex('idx_packages_status', '{{%packages}}', 'status');
        $this->createIndex('idx_packages_last_check', '{{%packages}}', 'last_check');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%packages}}');
    }
}
