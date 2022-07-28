<?php

namespace common\models;

use common\components\Helper;
use Yii;

/**
 * This is the model class for table "timetable".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $short_description
 * @property int|null $client_id
 * @property int|null $date
 * @property int|null $time_from
 * @property int|null $time_to
 * @property int|null $place_id
 * @property int|null $user_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Timetable extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'short_description'], 'string'],
            [['client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id', 'color_id'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'phone' => 'Телефон',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'client_id' => 'Клиент',
            'date' => 'День',
            'time_from' => 'Время от',
            'time_to' => 'Время до',
            'place_id' => 'Место',
            'user_id' => 'Пользователь',
            'color_id' => 'Цвет',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->id;

        // добавляем ID клиента из существующего, либо создаем нового
        if(!$client = Client::findOne(['phone' => $this->phone])) {
            $client = new Client();
            $client->name = $this->name;
            $client->phone = $this->phone;
            $client->save();
        }
        $this->client_id = $client->id;
        $this->color_id = $this->color_id ? $this->color_id : Color::COLOR_TIMETABLE_DEFAULT;
        return parent::beforeSave($insert);
    }

    /**
     * @param $form
     */
    public function addAttributes($form)
    {
        $this->name = $form->name;
        $this->date = $form->date;
        $this->time_from = $form->time_from;
        $this->time_to = $form->time_to;
        $this->place_id = $form->place_id;
        $this->description = $form->description;
        $this->phone = Helper::phoneFormat($form->phone);
    }
    public function getItemStyle()
    {
        if($this->color_id && ($color = Color::findOne($this->color_id))) {
            return $this->getStyles($color->background, $color->border, $color->text);
        }
        $color = Color::findOne(Color::COLOR_TIMETABLE_DEFAULT);
        return $this->getStyles($color->background, $color->border, $color->text);
    }
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['id' => 'place_id']);
    }
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getColorsArray()
    {

    }
}
