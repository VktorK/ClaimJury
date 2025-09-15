<?php

use yii\db\Migration;

class m250914_081700_remove_unused_columns_from_purchases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Удаляем колонки, которые перенесены в таблицу claims
        $this->dropColumn('{{%purchases}}', 'was_repaired_officially');
        $this->dropColumn('{{%purchases}}', 'repair_document_description');
        $this->dropColumn('{{%purchases}}', 'repair_document_date');
        $this->dropColumn('{{%purchases}}', 'defect_proof_type');
        $this->dropColumn('{{%purchases}}', 'defect_proof_document_description');
        $this->dropColumn('{{%purchases}}', 'defect_proof_document_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Восстанавливаем удаленные колонки
        $this->addColumn('{{%purchases}}', 'was_repaired_officially', $this->boolean()->comment('Товар ремонтировался официально'));
        $this->addColumn('{{%purchases}}', 'repair_document_description', $this->string(500)->comment('Описание документа о ремонте'));
        $this->addColumn('{{%purchases}}', 'repair_document_date', $this->date()->comment('Дата документа о ремонте'));
        $this->addColumn('{{%purchases}}', 'defect_proof_type', $this->string(50)->comment('Тип доказательства недостатка'));
        $this->addColumn('{{%purchases}}', 'defect_proof_document_description', $this->string(500)->comment('Описание документа доказательства'));
        $this->addColumn('{{%purchases}}', 'defect_proof_document_date', $this->date()->comment('Дата документа доказательства'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250914_081700_remove_unused_columns_from_purchases_table cannot be reverted.\n";

        return false;
    }
    */
}
