<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m221026_183659_user_cook
 */
class m221026_183659_user_cook extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_cook}}', [
            'id'                    => Schema::TYPE_PK,
            'user_id'               => Schema::TYPE_INTEGER . ' NOT NULL',
            'value'                 => Schema::TYPE_INTEGER,
            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_cook}}');
    }
}
