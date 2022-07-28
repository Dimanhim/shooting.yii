<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_roles".
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserRight extends \yii\db\ActiveRecord
{
    const RIGHT_1 = 'add_records_to_timetable';
    const RIGHT_2 = 'show_my_calendars';
    const RIGHT_3 = 'show_groups';

    private $_rights = [
        self::RIGHT_1 => 'Добавление записей в расписание',
        self::RIGHT_2 => 'Просмотр моих календарей',
        self::RIGHT_3 => 'Просмотр групп',
    ];




    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_rights';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'right_id'], 'required'],
            [['user_id'], 'integer'],
            [['right_id'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'right_id' => 'Role ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /*public function getRoleName()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }*/

    /**
     * @return array
     */
    public function getRights()
    {
        return $this->_rights;
    }

    public function getRightName()
    {
        if(array_key_exists($this->right_id, $this->_rights)) {
            return $this->_rights[$this->right_id];
        }
        return false;
    }
}
