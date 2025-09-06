<?php

use yii\db\Migration;

class m250906_193943_add_warranty_and_appeal_columns_to_purchases extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%purchases}}', 'warranty_period', $this->integer()->comment('Гарантийный срок (дни)'));
        $this->addColumn('{{%purchases}}', 'appeal_deadline', $this->date()->comment('Срок обращения'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%purchases}}', 'warranty_period');
        $this->dropColumn('{{%purchases}}', 'appeal_deadline');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_193943_add_warranty_and_appeal_columns_to_purchases cannot be reverted.\n";

        return false;
    }
    */
}
