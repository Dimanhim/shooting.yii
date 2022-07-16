<?php

namespace common\models;

use common\models\BaseModel;
use yii\base\Model;

class TimetableForm extends BaseModel
{
    public $name;
    public $description;
    public $short_description;
    public $client_id;
    public $date;
    public $time_from;
    public $time_to;
    public $place_id;
    public $user_id;
    public $phone;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['date', 'time_from', 'time_to'], 'required'],
            [['description', 'short_description'], 'string'],
            [['client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id'], 'integer'],
            [['name', 'phone'], 'string', 'length' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'client_id' => 'Клиент',
            'date' => 'День',
            'time_from' => 'Время от',
            'time_to' => 'Время до',
            'place_id' => 'Место',
            'user_id' => 'Пользователь',
            'phone' => 'Телефон',
        ];
    }

}
