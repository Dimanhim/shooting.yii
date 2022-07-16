<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220621_095954_colors
 */
class m220621_095954_colors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%colors}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING . ' NOT NULL',
            'description'           => Schema::TYPE_TEXT,
            'background'            => Schema::TYPE_STRING . ' NOT NULL',
            'border'                => Schema::TYPE_STRING,
            'text'                  => Schema::TYPE_STRING,

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
        $this->dropTable('{{%colors}}');
    }
}
