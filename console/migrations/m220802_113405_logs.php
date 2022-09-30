<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220802_113405_logs
 */
class m220802_113405_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%logs}}', [
            'id'                    => Schema::TYPE_PK,
            'is_new'                => Schema::TYPE_STRING,
            'is_deleted'            => Schema::TYPE_STRING,
            'timetable_id'          => Schema::TYPE_INTEGER . ' NOT NULL',
            'attribute'             => Schema::TYPE_STRING  . ' NOT NULL',
            'operation_id'          => Schema::TYPE_STRING,
            'change_from'           => Schema::TYPE_STRING  . ' NOT NULL',
            'change_to'             => Schema::TYPE_STRING  . ' NOT NULL',
            'user_id'               => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at'            => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%logs}}');
    }
}
