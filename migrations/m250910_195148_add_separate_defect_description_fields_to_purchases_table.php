<?php

use yii\db\Migration;

class m250910_195148_add_separate_defect_description_fields_to_purchases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем отдельные поля для разных типов описаний недостатков
        $this->addColumn('purchases', 'repair_defect_description', $this->text()->null()->comment('Недостаток согласно акту выполненных работ'));
        $this->addColumn('purchases', 'current_defect_description', $this->text()->null()->comment('Описание текущего недостатка'));
        $this->addColumn('purchases', 'expertise_defect_description', $this->text()->null()->comment('Описание недостатка при экспертизе'));
        
        // Переименовываем существующее поле для ясности
        $this->renameColumn('purchases', 'defect_description', 'general_defect_description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем добавленные поля
        $this->dropColumn('purchases', 'repair_defect_description');
        $this->dropColumn('purchases', 'current_defect_description');
        $this->dropColumn('purchases', 'expertise_defect_description');
        
        // Возвращаем старое название поля
        $this->renameColumn('purchases', 'general_defect_description', 'defect_description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250910_195148_add_separate_defect_description_fields_to_purchases_table cannot be reverted.\n";

        return false;
    }
    */
}
