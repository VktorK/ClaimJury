<?php

use yii\db\Migration;

/**
 * Handles adding tracking fields to table `{{%claims}}`.
 */
class m250907_150230_add_tracking_fields_to_claims_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Проверяем, существует ли таблица claims
        if ($this->db->getTableSchema('{{%claims}}') === null) {
            echo "Таблица claims не существует.\n";
            return false;
        }

        // Добавляем поля для отслеживания документов
        $this->addColumn('{{%claims}}', 'tracking_number', $this->string(50)->null()->comment('Трек-номер отправления'));
        $this->addColumn('{{%claims}}', 'document_sent_date', $this->integer()->null()->comment('Дата отправки документов'));
        $this->addColumn('{{%claims}}', 'document_received_date', $this->integer()->null()->comment('Дата получения документов'));
        $this->addColumn('{{%claims}}', 'tracking_status', $this->string(100)->null()->comment('Статус отслеживания'));
        $this->addColumn('{{%claims}}', 'tracking_details', $this->text()->null()->comment('Детали отслеживания (JSON)'));
        $this->addColumn('{{%claims}}', 'last_tracking_update', $this->integer()->null()->comment('Последнее обновление отслеживания'));

        // Создаем индекс для трек-номера
        $this->createIndex('idx-claims-tracking_number', '{{%claims}}', 'tracking_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем индекс
        $this->dropIndex('idx-claims-tracking_number', '{{%claims}}');
        
        // Удаляем поля
        $this->dropColumn('{{%claims}}', 'tracking_number');
        $this->dropColumn('{{%claims}}', 'document_sent_date');
        $this->dropColumn('{{%claims}}', 'document_received_date');
        $this->dropColumn('{{%claims}}', 'tracking_status');
        $this->dropColumn('{{%claims}}', 'tracking_details');
        $this->dropColumn('{{%claims}}', 'last_tracking_update');
    }
}