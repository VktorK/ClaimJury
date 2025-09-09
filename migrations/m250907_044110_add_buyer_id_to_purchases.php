<?php

use yii\db\Migration;

class m250907_044110_add_buyer_id_to_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем колонку buyer_id в таблицу purchases
        $this->addColumn('{{%purchases}}', 'buyer_id', $this->integer()->comment('ID покупателя'));
        
        // Создаем индекс для buyer_id
        $this->createIndex('idx-purchases-buyer_id', '{{%purchases}}', 'buyer_id');
        
        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk-purchases-buyer_id',
            '{{%purchases}}',
            'buyer_id',
            '{{%buyers}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey('fk-purchases-buyer_id', '{{%purchases}}');
        
        // Удаляем индекс
        $this->dropIndex('idx-purchases-buyer_id', '{{%purchases}}');
        
        // Удаляем колонку
        $this->dropColumn('{{%purchases}}', 'buyer_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250907_044110_add_buyer_id_to_purchases cannot be reverted.\n";

        return false;
    }
    */
}
