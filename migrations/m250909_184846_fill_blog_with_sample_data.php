<?php

use yii\db\Migration;

class m250909_184846_fill_blog_with_sample_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250909_184846_fill_blog_with_sample_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250909_184846_fill_blog_with_sample_data cannot be reverted.\n";

        return false;
    }
    */
}
