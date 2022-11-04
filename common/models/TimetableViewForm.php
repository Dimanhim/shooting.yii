<?php

namespace common\models;

use yii\base\Model;

class TimetableViewForm extends Model
{
    public $invite_persons;
    public $color_id;

    public function rules()
    {
        return [
            [['invite_persons', 'color_id', 'time_from'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'color_id' => 'Цвет',
            'invite_persons' => 'Пригласите пользователей',
        ];
    }
}
