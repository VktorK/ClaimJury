<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%profiles}}`.
 */
class m250905_142307_create_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%profiles}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'first_name' => $this->string(100),
            'last_name' => $this->string(100),
            'middle_name' => $this->string(100),
            'phone' => $this->string(20),
            'birth_date' => $this->date(),
            'gender' => $this->string(10),
            'address' => $this->text(),
            'city' => $this->string(100),
            'country' => $this->string(100),
            'postal_code' => $this->string(20),
            'avatar' => $this->string(255),
            'bio' => $this->text(),
            'website' => $this->string(255),
            'linkedin' => $this->string(255),
            'twitter' => $this->string(255),
            'facebook' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        $this->addForeignKey(
            'fk-profiles-user_id',
            '{{%profiles}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex('idx-profiles-user_id', '{{%profiles}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-profiles-user_id', '{{%profiles}}');
        $this->dropTable('{{%profiles}}');
    }
}
