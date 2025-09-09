<?php

use yii\db\Migration;

class m250907_061518_add_middle_name_to_buyers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%buyers}}', 'middleName', $this->string(100)->comment('Отчество'));
        $this->createIndex('idx-buyers-middleName', '{{%buyers}}', 'middleName');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-buyers-middleName', '{{%buyers}}');
        $this->dropColumn('{{%buyers}}', 'middleName');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250907_061518_add_middle_name_to_buyers_table cannot be reverted.\n";

        return false;
    }
    */
}
