<?php

use yii\db\Migration;

/**
 * Cleanup temporary columns in categories table
 */
class m250907_111818_cleanup_temp_columns_in_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Удаляем временные колонки если они существуют
        try {
            $this->dropColumn('{{%categories}}', 'created_at_temp');
        } catch (Exception $e) {
            // Колонка может не существовать
        }
        
        try {
            $this->dropColumn('{{%categories}}', 'updated_at_temp');
        } catch (Exception $e) {
            // Колонка может не существовать
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Ничего не делаем при откате
    }
}