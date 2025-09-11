<?php

use yii\db\Migration;

class m250911_162412_add_claim_id_to_packages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем колонку claim_id
        $this->addColumn('{{%packages}}', 'claim_id', $this->integer()->null()->comment('ID претензии'));
        
        // Добавляем внешний ключ
        $this->addForeignKey(
            'fk_packages_claim_id',
            '{{%packages}}',
            'claim_id',
            '{{%claims}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        // Добавляем индекс для оптимизации
        $this->createIndex('idx_packages_claim_id', '{{%packages}}', 'claim_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey('fk_packages_claim_id', '{{%packages}}');
        
        // Удаляем индекс
        $this->dropIndex('idx_packages_claim_id', '{{%packages}}');
        
        // Удаляем колонку
        $this->dropColumn('{{%packages}}', 'claim_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250911_162412_add_claim_id_to_packages_table cannot be reverted.\n";

        return false;
    }
    */
}
