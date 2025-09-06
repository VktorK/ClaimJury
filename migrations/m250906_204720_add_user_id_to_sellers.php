<?php

use yii\db\Migration;

class m250906_204720_add_user_id_to_sellers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Проверяем, существует ли колонка user_id
        $tableSchema = $this->db->getTableSchema('{{%sellers}}');
        if (!isset($tableSchema->columns['user_id'])) {
            // Сначала добавляем колонку как nullable
            $this->addColumn('{{%sellers}}', 'user_id', $this->integer()->comment('ID пользователя'));
        }
        
        // Заполняем существующие записи user_id = 1 (первый пользователь)
        $this->update('{{%sellers}}', ['user_id' => 1], ['user_id' => null]);
        
        // Теперь делаем колонку NOT NULL
        $this->alterColumn('{{%sellers}}', 'user_id', $this->integer()->notNull()->comment('ID пользователя'));
        
        // Создаем индекс и внешний ключ
        try {
            $this->createIndex('idx-sellers-user_id', '{{%sellers}}', 'user_id');
        } catch (Exception $e) {
            // Индекс уже существует
        }
        
        try {
            $this->addForeignKey(
                'fk-sellers-user_id',
                '{{%sellers}}',
                'user_id',
                '{{%user}}',
                'id',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Внешний ключ уже существует
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sellers-user_id', '{{%sellers}}');
        $this->dropIndex('idx-sellers-user_id', '{{%sellers}}');
        $this->dropColumn('{{%sellers}}', 'user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250906_204720_add_user_id_to_sellers cannot be reverted.\n";

        return false;
    }
    */
}
