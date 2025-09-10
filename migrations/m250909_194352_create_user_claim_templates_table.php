<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_claim_templates}}`.
 */
class m250909_194352_create_user_claim_templates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_claim_templates}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('ID пользователя'),
            'original_template_id' => $this->integer()->comment('ID оригинального шаблона (может быть NULL для полностью пользовательских)'),
            'name' => $this->string(255)->notNull()->comment('Название шаблона'),
            'type' => $this->string(255)->notNull()->comment('Тип претензии'),
            'description' => $this->string(255)->comment('Описание шаблона'),
            'template_content' => $this->text()->notNull()->comment('Содержание шаблона'),
            'is_favorite' => $this->boolean()->defaultValue(false)->comment('Избранный шаблон'),
            'status' => $this->integer()->defaultValue(1)->comment('Статус (0-неактивен, 1-активен)'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        // Индексы
        $this->createIndex('idx-user_claim_templates-user_id', '{{%user_claim_templates}}', 'user_id');
        $this->createIndex('idx-user_claim_templates-type', '{{%user_claim_templates}}', 'type');
        $this->createIndex('idx-user_claim_templates-status', '{{%user_claim_templates}}', 'status');
        $this->createIndex('idx-user_claim_templates-original_template_id', '{{%user_claim_templates}}', 'original_template_id');
        $this->createIndex('idx-user_claim_templates-is_favorite', '{{%user_claim_templates}}', 'is_favorite');

        // Внешние ключи
        $this->addForeignKey(
            'fk-user_claim_templates-user_id',
            '{{%user_claim_templates}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_claim_templates-original_template_id',
            '{{%user_claim_templates}}',
            'original_template_id',
            '{{%claim_templates}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_claim_templates-original_template_id', '{{%user_claim_templates}}');
        $this->dropForeignKey('fk-user_claim_templates-user_id', '{{%user_claim_templates}}');
        $this->dropTable('{{%user_claim_templates}}');
    }
}