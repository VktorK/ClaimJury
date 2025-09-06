<?php

use yii\db\Migration;

class m250906_205657_add_purchases_id_to_sellers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sellers}}', 'purchases_id', $this->integer()->comment('ID покупки'));
        $this->createIndex('idx-sellers-purchases_id', '{{%sellers}}', 'purchases_id');
        $this->addForeignKey(
            'fk-sellers-purchases_id',
            '{{%sellers}}',
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
        $this->dropForeignKey('fk-sellers-purchases_id', '{{%sellers}}');
        $this->dropIndex('idx-sellers-purchases_id', '{{%sellers}}');
        $this->dropColumn('{{%sellers}}', 'purchases_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_205657_add_purchases_id_to_sellers cannot be reverted.\n";

        return false;
    }
    */
}
