<?php

use yii\db\Migration;

/**
 * Fix categories table timestamps to integer format
 */
class m250907_111440_fix_categories_timestamps_to_integer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Сначала удаляем временные колонки если они существуют
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
        
        // Теперь добавляем временные колонки с integer типом
        $this->addColumn('{{%categories}}', 'created_at_temp', $this->integer()->notNull()->comment('Дата создания (временная)'));
        $this->addColumn('{{%categories}}', 'updated_at_temp', $this->integer()->null()->comment('Дата обновления (временная)'));
        
        // Копируем данные из timestamp колонок в integer колонки
        $this->execute('UPDATE {{%categories}} SET created_at_temp = UNIX_TIMESTAMP(created_at), updated_at_temp = CASE WHEN updated_at IS NULL THEN NULL ELSE UNIX_TIMESTAMP(updated_at) END');
        
        // Удаляем старые колонки
        $this->dropColumn('{{%categories}}', 'created_at');
        $this->dropColumn('{{%categories}}', 'updated_at');
        
        // Переименовываем временные колонки в основные
        $this->renameColumn('{{%categories}}', 'created_at_temp', 'created_at');
        $this->renameColumn('{{%categories}}', 'updated_at_temp', 'updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Возвращаем обратно к timestamp формату
        $this->addColumn('{{%categories}}', 'created_at_temp', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->comment('Дата создания (временная)'));
        $this->addColumn('{{%categories}}', 'updated_at_temp', $this->timestamp()->null()->comment('Дата обновления (временная)'));
        
        // Копируем данные из integer колонок в timestamp колонки
        $this->execute('UPDATE {{%categories}} SET created_at_temp = FROM_UNIXTIME(created_at), updated_at_temp = CASE WHEN updated_at IS NULL THEN NULL ELSE FROM_UNIXTIME(updated_at) END');
        
        // Удаляем старые колонки
        $this->dropColumn('{{%categories}}', 'created_at');
        $this->dropColumn('{{%categories}}', 'updated_at');
        
        // Переименовываем временные колонки в основные
        $this->renameColumn('{{%categories}}', 'created_at_temp', 'created_at');
        $this->renameColumn('{{%categories}}', 'updated_at_temp', 'updated_at');
    }
}