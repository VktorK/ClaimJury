<?php

use yii\db\Migration;

class m250906_193215_add_default_value_to_seller_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%purchases}}', 'seller_name', $this->string(255)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%purchases}}', 'seller_name', $this->string(255));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_193215_add_default_value_to_seller_name cannot be reverted.\n";

        return false;
    }
    */
}
