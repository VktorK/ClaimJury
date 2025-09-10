<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%blog_categories}}`.
 */
class m250909_184626_create_blog_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Название категории'),
            'slug' => $this->string(255)->notNull()->unique()->comment('URL-адрес'),
            'description' => $this->text()->comment('Описание категории'),
            'status' => $this->integer()->defaultValue(1)->comment('Статус (0-неактивна, 1-активна)'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        $this->createIndex('idx-blog_categories-status', '{{%blog_categories}}', 'status');
        $this->createIndex('idx-blog_categories-slug', '{{%blog_categories}}', 'slug');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_categories}}');
    }
}
