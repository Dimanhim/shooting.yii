<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220620_192551_clients
 */
class m220620_192551_clients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clients}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING,
            'email'                 => Schema::TYPE_STRING,
            'phone'                 => Schema::TYPE_STRING,

            'is_active'             => Schema::TYPE_SMALLINT,
            'deleted'               => Schema::TYPE_SMALLINT,
            'position'              => Schema::TYPE_INTEGER,
            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%clients}}');
    }
}
