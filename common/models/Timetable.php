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
    const BASE_ROW_hEIGHT = 40;
    const DIFF_COUNT_SECONDS = 60 * 30;

    private $_new_record = false;

    private $_logged_attrubutes  = [
        'date', 'time_from', 'time_to', 'phone', 'qty', 'service_id', 'place_id', 'description'
    ];
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
            [['client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id', 'color_id', 'trainer_id', 'service_id'], 'integer'],
            [['name', 'phone', 'qty'], 'string', 'max' => 255],
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
            'qty' => 'Количество человек',
            'trainer_id' => 'Инструктор',
            'service_id' => 'Услуга',
            'date' => 'День',
            'time_from' => 'Время от',
            'time_to' => 'Время до',
            'place_id' => 'Стрельбище',
            'user_id' => 'Пользователь',
            'color_id' => 'Цвет',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->setLog($changedAttributes);
        return parent::afterSave($insert, $changedAttributes);
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
        $this->_new_record = $this->isNewRecord;
        return parent::beforeSave($insert);
    }

    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['id' => 'trainer_id']);
    }

    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
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
    public function getItemStyle($height = true)
    {
        $ratio = ($this->time_to - $this->time_from) / self::DIFF_COUNT_SECONDS * self::BASE_ROW_hEIGHT;
        $heightStr = $height ? 'height: '.self::getRowHeight($ratio).'px;' : '';
        if($this->color_id && ($color = Color::findOne($this->color_id))) {
            return $this->getStyles($color->background, $color->border, $color->text) . $heightStr;
        }
        $color = Color::findOne(Color::COLOR_TIMETABLE_DEFAULT);
        return $this->getStyles($color->background, $color->border, $color->text) . $heightStr;
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

    public static function getRowHeight($height)
    {
        $paddingTop = 1;
        $paddingBottom = 1;
        return $height - $paddingTop - $paddingBottom;
    }

    public function setLog($changedAttributes)
    {
        file_put_contents('info-log.txt', print_r($changedAttributes, true)."\n", FILE_APPEND);
        //$is_new = $this->created_at == $this->updated_at;
        $is_new = $this->_new_record;
        $operation_id = uniqid();
        if($changedAttributes) {
            $newAttributes = $this->attributes;
            foreach ($changedAttributes as $attributeName => $attributeValue) {
                if(in_array($attributeName, $this->_logged_attrubutes)) {
                    if($attributeValue != $newAttributes[$attributeName]) {
                        Log::setLog($this->id, $is_new, $attributeName, $attributeValue, $newAttributes[$attributeName], $operation_id);
                    }
                }
            }
        }
    }
}
