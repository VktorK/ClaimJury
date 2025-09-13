<?php

use yii\db\Migration;

/**
 * Handles adding repair claim template.
 */
class m250913_120000_add_repair_claim_template extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('claim_templates', [
            'name' => 'Претензия на ремонт товара',
            'type' => 'repair',
            'description' => 'Шаблон претензии для ремонта товара с производственным браком',
            'template_content' => '                                                                         Кому: {SELLER_NAME}
                                                                         ОГРН: {SELLER_OGRN}
                                                                         Адрес: {SELLER_ADDRESS}

                                                                         От: {BUYER_FULL_NAME}
                                                                         Адрес: {BUYER_ADDRESS}
                                                                         Телефон: {BUYER_PHONE}

                                                ПРЕТЕНЗИЯ

{PURCHASE_DATE} с {SELLER_NAME} был заключен договор купли-продажи товара {PRODUCT_NAME}(модель: {PRODUCT_MODEL}, серийный номер: {PRODUCT_SERIAL}).Срок гарантии составляет {PURCHASE_WARRANTY}. Покупка подтверждается наличием кассового чека. В процессе эксплуатации в товаре выявился недостаток : {CURRENT_DEFECT_DESCRIPTION}. Учитывая, что недостаток возник за пределами гарантийного срока, но в пределах двух лет, и согласно {DEFECT_PROOF_TYPE} от {DEFECT_PROOF_DOCUMENT_DATE} указанный недостаток носит производственный характер. 
    На основании вышеизложенного и руководствуясь положением статьи 19,18 Закона РФ "О Защите прав потребителей"
                                                              Прошу Вас
1) Безвозмездно устранить имеющиеся недостатки в товаре 
Приложение:
- Копия товарного чека
- Копия гарантийного талона
- Фотографии недостатков

Дата: {CURRENT_DATE}
Подпись: _________________',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('claim_templates', ['name' => 'Претензия на ремонт товара']);
    }
}
