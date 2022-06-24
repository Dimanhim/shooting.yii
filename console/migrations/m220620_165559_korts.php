<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220620_165559_korts
 */
class m220620_165559_korts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%places}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'street_id'             => Schema::TYPE_STRING,
            'name'                  => Schema::TYPE_STRING . ' NOT NULL',
            'description'           => Schema::TYPE_TEXT,
            'short_description'     => Schema::TYPE_TEXT,
            'price'                 => Schema::TYPE_INTEGER,
            'price_2'               => Schema::TYPE_INTEGER,
            'price_3'               => Schema::TYPE_INTEGER,
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
        $this->dropTable('{{%places}}');
    }
}
