<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220724_143109_user_roles
 */
class m220724_143109_user_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_roles}}', [
            'id'                    => Schema::TYPE_PK,

            'user_id'               => Schema::TYPE_INTEGER . ' NOT NULL',
            'role_id'               => Schema::TYPE_INTEGER . ' NOT NULL',

            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_roles}}');
    }
}
