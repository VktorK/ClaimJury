<?php

use yii\db\Migration;

class m250905_142612_create_profiles_for_existing_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $users = $this->db->createCommand('SELECT id FROM {{%user}}')->queryAll();
        
        foreach ($users as $user) {
            $this->insert('{{%profiles}}', [
                'user_id' => $user['id'],
                'created_at' => time(),
                'updated_at' => time(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем все профили
        $this->delete('{{%profiles}}');
    }
}
