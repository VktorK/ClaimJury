<?php

use yii\db\Migration;

class m250913_070333_add_model_to_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'model', $this->string(255)->comment('Модель товара'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'model');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250913_070333_add_model_to_products_table cannot be reverted.\n";

        return false;
    }
    */
}
