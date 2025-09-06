<?php

use yii\db\Migration;

class m250906_192404_add_seller_id_to_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%purchases}}', 'seller_id', $this->integer()->comment('ID продавца'));
        $this->createIndex('idx-purchases-seller_id', '{{%purchases}}', 'seller_id');
        $this->addForeignKey(
            'fk-purchases-seller_id',
            '{{%purchases}}',
            'seller_id',
            '{{%sellers}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-purchases-seller_id', '{{%purchases}}');
        $this->dropIndex('idx-purchases-seller_id', '{{%purchases}}');
        $this->dropColumn('{{%purchases}}', 'seller_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_192404_add_seller_id_to_purchases cannot be reverted.\n";

        return false;
    }
    */
}
