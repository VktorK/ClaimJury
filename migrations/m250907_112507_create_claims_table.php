<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%claims}}`.
 */
class m250907_112507_create_claims_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Проверяем, существует ли таблица
        if ($this->db->getTableSchema('{{%claims}}') === null) {
            $this->createTable('{{%claims}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
            'purchase_id' => $this->integer()->notNull()->comment('ID покупки'),
            'title' => $this->string(255)->notNull()->comment('Название претензии'),
            'description' => $this->text()->comment('Описание претензии'),
            'claim_type' => $this->string(50)->notNull()->comment('Тип претензии'),
            'status' => $this->string(20)->notNull()->defaultValue('pending')->comment('Статус претензии'),
            'claim_date' => $this->integer()->notNull()->comment('Дата подачи претензии'),
            'resolution_date' => $this->integer()->null()->comment('Дата решения претензии'),
            'resolution_notes' => $this->text()->comment('Примечания по решению'),
            'amount_claimed' => $this->decimal(10, 2)->null()->comment('Сумма претензии'),
            'amount_resolved' => $this->decimal(10, 2)->null()->comment('Сумма решения'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
            ]);
            
            // Создаем индексы
            $this->createIndex('idx-claims-user_id', '{{%claims}}', 'user_id');
            $this->createIndex('idx-claims-purchase_id', '{{%claims}}', 'purchase_id');
            $this->createIndex('idx-claims-status', '{{%claims}}', 'status');
            $this->createIndex('idx-claims-claim_date', '{{%claims}}', 'claim_date');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем индексы
        try {
            $this->dropIndex('idx-claims-claim_date', '{{%claims}}');
            $this->dropIndex('idx-claims-status', '{{%claims}}');
            $this->dropIndex('idx-claims-purchase_id', '{{%claims}}');
            $this->dropIndex('idx-claims-user_id', '{{%claims}}');
        } catch (Exception $e) {
            // Индексы могут не существовать
        }
        
        $this->dropTable('{{%claims}}');
    }
}