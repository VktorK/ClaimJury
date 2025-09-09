<?php

use yii\db\Migration;

/**
 * Add foreign keys to claims table
 */
class m250907_112854_add_foreign_keys_to_claims_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk-claims-user_id',
            '{{%claims}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-claims-purchase_id',
            '{{%claims}}',
            'purchase_id',
            '{{%purchases}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешние ключи
        $this->dropForeignKey('fk-claims-purchase_id', '{{%claims}}');
        $this->dropForeignKey('fk-claims-user_id', '{{%claims}}');
    }
}