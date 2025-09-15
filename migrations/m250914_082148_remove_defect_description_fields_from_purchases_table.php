<?php

use yii\db\Migration;

class m250914_082148_remove_defect_description_fields_from_purchases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Удаляем поля описания недостатков, которые перенесены в таблицу claims
        $this->dropColumn('{{%purchases}}', 'general_defect_description');
        $this->dropColumn('{{%purchases}}', 'repair_defect_description');
        $this->dropColumn('{{%purchases}}', 'current_defect_description');
        $this->dropColumn('{{%purchases}}', 'expertise_defect_description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Восстанавливаем удаленные поля описания недостатков
        $this->addColumn('{{%purchases}}', 'general_defect_description', $this->text()->comment('Общее описание недостатка'));
        $this->addColumn('{{%purchases}}', 'repair_defect_description', $this->text()->comment('Описание недостатка согласно акту выполненных работ'));
        $this->addColumn('{{%purchases}}', 'current_defect_description', $this->text()->comment('Описание текущего недостатка'));
        $this->addColumn('{{%purchases}}', 'expertise_defect_description', $this->text()->comment('Описание недостатка для экспертизы'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250914_082148_remove_defect_description_fields_from_purchases_table cannot be reverted.\n";

        return false;
    }
    */
}
