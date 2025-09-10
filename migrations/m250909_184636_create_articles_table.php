<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles}}`.
 */
class m250909_184636_create_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%articles}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Заголовок статьи'),
            'slug' => $this->string(255)->notNull()->unique()->comment('URL-адрес'),
            'excerpt' => $this->text()->comment('Краткое описание'),
            'content' => $this->text()->notNull()->comment('Содержание статьи'),
            'image' => $this->string(255)->comment('Изображение'),
            'category_id' => $this->integer()->notNull()->comment('ID категории'),
            'status' => $this->integer()->defaultValue(0)->comment('Статус (0-черновик, 1-опубликована)'),
            'views' => $this->integer()->defaultValue(0)->comment('Количество просмотров'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата обновления'),
        ]);

        $this->createIndex('idx-articles-category_id', '{{%articles}}', 'category_id');
        $this->createIndex('idx-articles-status', '{{%articles}}', 'status');
        $this->createIndex('idx-articles-slug', '{{%articles}}', 'slug');
        $this->createIndex('idx-articles-created_at', '{{%articles}}', 'created_at');

        $this->addForeignKey(
            'fk-articles-category_id',
            '{{%articles}}',
            'category_id',
            '{{%blog_categories}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%articles}}');
    }
}
