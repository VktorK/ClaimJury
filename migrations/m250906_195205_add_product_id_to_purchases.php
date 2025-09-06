<?php

use yii\db\Migration;

class m250906_195205_add_product_id_to_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%purchases}}', 'product_id', $this->integer()->comment('ID товара'));
        $this->createIndex('idx-purchases-product_id', '{{%purchases}}', 'product_id');
        $this->addForeignKey(
            'fk-purchases-product_id',
            '{{%purchases}}',
            'product_id',
            '{{%products}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-purchases-product_id', '{{%purchases}}');
        $this->dropIndex('idx-purchases-product_id', '{{%purchases}}');
        $this->dropColumn('{{%purchases}}', 'product_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_195205_add_product_id_to_purchases cannot be reverted.\n";

        return false;
    }
    */
}
