<?php

use yii\db\Migration;

class m250906_201857_add_serial_number_and_warranty_to_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'serial_number', $this->string(255)->comment('Серийный номер'));
        $this->addColumn('{{%products}}', 'warranty_period', $this->integer()->comment('Гарантийный срок в месяцах'));
        
        $this->createIndex('idx-products-serial_number', '{{%products}}', 'serial_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-products-serial_number', '{{%products}}');
        $this->dropColumn('{{%products}}', 'warranty_period');
        $this->dropColumn('{{%products}}', 'serial_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_201857_add_serial_number_and_warranty_to_products cannot be reverted.\n";

        return false;
    }
    */
}
