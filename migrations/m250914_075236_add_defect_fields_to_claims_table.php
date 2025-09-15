<?php

use yii\db\Migration;

class m250914_075236_add_defect_fields_to_claims_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Добавляем поля для информации о ремонте
        $this->addColumn('{{%claims}}', 'was_repaired_officially', $this->boolean()->defaultValue(false)->comment('Товар ремонтировался официально'));
        $this->addColumn('{{%claims}}', 'repair_document_description', $this->text()->comment('Описание документа о ремонте'));
        $this->addColumn('{{%claims}}', 'repair_document_date', $this->date()->comment('Дата документа о ремонте'));
        
        // Добавляем поля для описания недостатков
        $this->addColumn('{{%claims}}', 'repair_defect_description', $this->text()->comment('Описание недостатка согласно акту выполненных работ'));
        $this->addColumn('{{%claims}}', 'current_defect_description', $this->text()->comment('Описание текущего недостатка'));
        $this->addColumn('{{%claims}}', 'expertise_defect_description', $this->text()->comment('Описание недостатка для экспертизы'));
        
        // Добавляем поля для доказательств недостатка
        $this->addColumn('{{%claims}}', 'defect_proof_type', $this->string(50)->comment('Тип доказательства недостатка'));
        $this->addColumn('{{%claims}}', 'defect_proof_document_description', $this->text()->comment('Описание документа доказательства'));
        $this->addColumn('{{%claims}}', 'defect_proof_document_date', $this->date()->comment('Дата документа доказательства'));
        $this->addColumn('{{%claims}}', 'defect_similarity', $this->boolean()->comment('Недостаток аналогичный указанному'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем добавленные поля
        $this->dropColumn('{{%claims}}', 'was_repaired_officially');
        $this->dropColumn('{{%claims}}', 'repair_document_description');
        $this->dropColumn('{{%claims}}', 'repair_document_date');
        $this->dropColumn('{{%claims}}', 'repair_defect_description');
        $this->dropColumn('{{%claims}}', 'current_defect_description');
        $this->dropColumn('{{%claims}}', 'expertise_defect_description');
        $this->dropColumn('{{%claims}}', 'defect_proof_type');
        $this->dropColumn('{{%claims}}', 'defect_proof_document_description');
        $this->dropColumn('{{%claims}}', 'defect_proof_document_date');
        $this->dropColumn('{{%claims}}', 'defect_similarity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250914_075236_add_defect_fields_to_claims_table cannot be reverted.\n";

        return false;
    }
    */
}
