<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220802_114551_services
 */
class m220802_114551_services extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%services}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING . ' NOT NULL',
            'short_description'     => Schema::TYPE_TEXT,
            'description'           => Schema::TYPE_TEXT,
            'price'                 => Schema::TYPE_INTEGER,

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
        $this->dropTable('{{%services}}');
    }
}
