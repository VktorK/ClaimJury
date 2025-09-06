<?php

use yii\db\Migration;

class m250906_205645_add_purchases_id_to_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'purchases_id', $this->integer()->comment('ID покупки'));
        $this->createIndex('idx-products-purchases_id', '{{%products}}', 'purchases_id');
        $this->addForeignKey(
            'fk-products-purchases_id',
            '{{%products}}',
            'purchases_id',
            '{{%purchases}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products-purchases_id', '{{%products}}');
        $this->dropIndex('idx-products-purchases_id', '{{%products}}');
        $this->dropColumn('{{%products}}', 'purchases_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_205645_add_purchases_id_to_products cannot be reverted.\n";

        return false;
    }
    */
}
