<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220910_101352_repeats
 */
class m220910_101352_repeats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%repeats}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'timetable_id'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'type'                  => Schema::TYPE_STRING . ' NOT NULL',
            'values'           => Schema::TYPE_TEXT,
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
        $this->dropTable('{{%repeats}}');
    }
}
