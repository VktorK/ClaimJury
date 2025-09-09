<?php

use yii\db\Migration;

/**
 * Handles dropping the column `title` from table `{{%claims}}`.
 */
class m250907_141038_remove_title_from_claims_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Проверяем, существует ли колонка title
        if ($this->db->getTableSchema('{{%claims}}')->getColumn('title') !== null) {
            $this->dropColumn('{{%claims}}', 'title');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Добавляем колонку title обратно
        $this->addColumn('{{%claims}}', 'title', $this->string(255)->notNull()->comment('Название претензии'));
    }
}