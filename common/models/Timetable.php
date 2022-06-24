<?php

namespace common\models;

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
class Timetable extends \yii\db\ActiveRecord
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
            [['client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'client_id' => 'Клиент',
            'date' => 'День',
            'time_from' => 'Время от',
            'time_to' => 'Время до',
            'place_id' => 'Место',
            'user_id' => 'Пользователь',
        ];
    }
}
