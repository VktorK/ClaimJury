<?php

use yii\db\Migration;

class m250910_191313_add_repair_and_defect_proof_fields_to_purchases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Поля для информации о ремонте в официальном сервисном центре
        $this->addColumn('purchases', 'was_repaired_officially', $this->boolean()->defaultValue(false)->comment('Ремонтировался ли товар в официальном сервисном центре'));
        $this->addColumn('purchases', 'repair_document_description', $this->string(500)->null()->comment('Описание и номер акта выполненных работ'));
        $this->addColumn('purchases', 'repair_document_date', $this->date()->null()->comment('Дата выдачи документа о ремонте'));
        
        // Поля для доказательств недостатка
        $this->addColumn('purchases', 'defect_proof_type', $this->string(50)->null()->comment('Тип доказательства недостатка (quality_check, independent_expertise, no_proof)'));
        $this->addColumn('purchases', 'defect_proof_document_description', $this->string(500)->null()->comment('Описание и номер документа о доказательстве недостатка'));
        $this->addColumn('purchases', 'defect_proof_document_date', $this->date()->null()->comment('Дата выдачи документа о доказательстве недостатка'));
        $this->addColumn('purchases', 'defect_description', $this->text()->null()->comment('Краткое описание недостатка'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем добавленные поля
        $this->dropColumn('purchases', 'was_repaired_officially');
        $this->dropColumn('purchases', 'repair_document_description');
        $this->dropColumn('purchases', 'repair_document_date');
        $this->dropColumn('purchases', 'defect_proof_type');
        $this->dropColumn('purchases', 'defect_proof_document_description');
        $this->dropColumn('purchases', 'defect_proof_document_date');
        $this->dropColumn('purchases', 'defect_description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250910_191313_add_repair_and_defect_proof_fields_to_purchases_table cannot be reverted.\n";

        return false;
    }
    */
}
