<?php

use yii\db\Migration;

class m250909_185229_fill_blog_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создаем категории блога
        $categories = [
            [
                'name' => 'Юридические технологии',
                'slug' => 'legal-tech',
                'description' => 'Новости и статьи о современных технологиях в юридической сфере',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'Гражданское право',
                'slug' => 'civil-law',
                'description' => 'Статьи по гражданскому праву, договорам и сделкам',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'Арбитражные дела',
                'slug' => 'arbitration',
                'description' => 'Практические советы по ведению арбитражных дел',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'Корпоративное право',
                'slug' => 'corporate-law',
                'description' => 'Вопросы корпоративного управления и права',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'Новости права',
                'slug' => 'legal-news',
                'description' => 'Актуальные новости в области законодательства',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'Практические советы',
                'slug' => 'practical-tips',
                'description' => 'Полезные советы для юристов и юридических компаний',
                'status' => 1,
                'created_at' => time(),
                'updated_at' => time(),
            ],
        ];

        foreach ($categories as $category) {
            $this->insert('{{%blog_categories}}', $category);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250909_185229_fill_blog_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250909_185229_fill_blog_data cannot be reverted.\n";

        return false;
    }
    */
}
