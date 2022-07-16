<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220620_165925_timetable
 */
class m220620_165925_timetable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%timetable}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'name'                  => Schema::TYPE_STRING,
            'phone'                 => Schema::TYPE_STRING,
            'description'           => Schema::TYPE_TEXT,
            'short_description'     => Schema::TYPE_TEXT,
            'client_id'             => Schema::TYPE_INTEGER,
            'date'                  => Schema::TYPE_INTEGER,
            'time_from'             => Schema::TYPE_INTEGER,
            'time_to'               => Schema::TYPE_INTEGER,
            'place_id'              => Schema::TYPE_INTEGER,
            'user_id'               => Schema::TYPE_INTEGER,
            'color_id'              => Schema::TYPE_INTEGER,

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
        $this->dropTable('{{%timetable}}');
    }
}
