<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%claim_templates}}`.
 */
class m250909_190136_create_claim_templates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%claim_templates}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Название шаблона'),
            'type' => $this->string(255)->notNull()->comment('Тип претензии'),
            'description' => $this->string(255)->comment('Описание шаблона'),
            'template_content' => $this->text()->notNull()->comment('Содержание шаблона'),
            'status' => $this->integer()->defaultValue(1)->comment('Статус (0-неактивен, 1-активен)'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        $this->createIndex('idx-claim_templates-type', '{{%claim_templates}}', 'type');
        $this->createIndex('idx-claim_templates-status', '{{%claim_templates}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%claim_templates}}');
    }
}
