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
    const DIFF_COUNT_SECONDS = 1800;

    const REPEAT_EVERY_DAY = 1;
    const REPEAT_EVERY_WEEK = 2;
    const REPEAT_EVERY_MONTH = 3;

    private $_new_record = false;
    public $repeat_check;

    //public $repeat_day_begin;               // дата начала
    //public $repeat_type;                    // ежедневно, еженедельно и т.д.
    //public $repeat_type_values = [];       // дни недели и т.д.

    private $repeat_data = [
        self::REPEAT_EVERY_DAY => [
            'name' => 'Ежедневно',
            'type_name' => 'Дни',
            'btn_type' => 'checkbox',
            'icon' => '<i class="bi bi-calendar-day"></i>',
            'values' => [
                100 => 'Все',
                1 => 'Пн',
                2 => 'Вт',
                3 => 'Ср',
                4 => 'Чт',
                5 => 'Пт',
                6 => 'Сб',
                7 => 'Вс',
            ],
        ],
        self::REPEAT_EVERY_WEEK => [
            'name' => 'Еженедельно',
            'type_name' => 'Недели',
            'btn_type' => 'radio',
            'icon' => '<i class="bi bi-calendar-day"></i>',
            'values' => [
                1 => 'Каждую неделю',
                2 => 'Через одну неделю',
            ],
        ],
        self::REPEAT_EVERY_MONTH => [
            'name' => 'Ежемесячно',
            'type_name' => 'Месяцы',
            'btn_type' => 'checkbox',
            'icon' => '<i class="bi bi-calendar-day"></i>',
            'values' => [
                100 => 'Все',
                1 => 'Янв',
                2 => 'Фев',
                3 => 'Мар',
                4 => 'Апр',
                5 => 'Май',
                6 => 'Июн',
                7 => 'Июл',
                8 => 'Авг',
                9 => 'Сен',
                10 => 'Окт',
                11 => 'Ноя',
                12 => 'Дек',
            ],
        ],
    ];

    private $_logged_attrubutes  = [
        'date', 'time_from', 'time_to', 'phone', 'qty', 'service_id', 'service_name', 'place_id', 'description', 'name',
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
            [['client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id', 'color_id', 'trainer_id', 'service_id', 'repeat_id', 'repeat_check'], 'integer'],
            [['name', 'phone', 'qty', 'service_name'], 'string', 'max' => 255],
            [['repeat_day_begin', 'repeat_type', 'repeat_type_values'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'client_id' => 'Клиент',
            'qty' => 'Количество человек',
            'trainer_id' => 'Инструктор',
            'service_id' => 'Услуга',
            'service_name' => 'Услуга',
            'date' => 'День',
            'time_from' => 'Время от',
            'time_to' => 'Время до',
            'place_id' => 'Стрельбище',
            'user_id' => 'Пользователь',
            'color_id' => 'Цвет',

            'repeat_id' => 'Зациклить',
            'repeat_check' => 'Зациклить',
            'repeat_day_begin' => 'Начало',
            'repeat_type' => 'Повтор',
            'repeat_type_values' => '',
        ];
    }

    public function afterFind()
    {
        if($this->repeat_type_values) {
            $this->repeat_type_values = explode(',', $this->repeat_type_values);
        }
        if($this->repeat_day_begin) {
            $this->repeat_day_begin = date('d.m.Y',$this->repeat_day_begin);
        }
        return parent::afterFind();
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

        if($this->repeat_type_values) {
            $this->repeat_type_values = implode(',', $this->repeat_type_values);
        }
        if($this->repeat_day_begin) {
            $this->repeat_day_begin = strtotime($this->repeat_day_begin);
        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        //file_put_contents('info-log.txt', 'delete'.print_r($this->attributes, true)."\n", FILE_APPEND);
        $this->setDeleteLog();
        return parent::beforeDelete();
    }

    public function getTrainer()
    {
        return $this->hasOne(Trainer::className(), ['id' => 'trainer_id']);
    }

    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    public function getRepeatData()
    {
        return $this->repeat_data;
    }
    public function getRepeatDataNames()
    {
        $data = $this->getRepeatData();
        $result = [];
        foreach($data as $key => $value) {
            $result[$key] = $value['name'];
        }
        return $result;
    }
    public function getOptionsForDropdown()
    {
        $data = $this->getRepeatData();
        $result = [];
        foreach($data as $key => $value) {
            $result[$key] = [
                'data-type-id' => $key,
            ];
        }
        return $result;
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

    public function getItemHeight()
    {
        return ($this->time_to - $this->time_from) / self::DIFF_COUNT_SECONDS * self::BASE_ROW_hEIGHT;
    }

    public function getItemRows()
    {
        return ($this->time_to - $this->time_from) / self::DIFF_COUNT_SECONDS;
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
        return $height;
        $paddingTop = 1;
        $paddingBottom = 1;
        return $height - $paddingTop - $paddingBottom;
    }

    public function setLog($changedAttributes, $is_deleted = null)
    {
        //file_put_contents('info-log.txt', print_r($changedAttributes, true)."\n", FILE_APPEND);
        //$is_new = $this->created_at == $this->updated_at;
        $is_new = $this->_new_record;
        $operation_id = uniqid();
        if($changedAttributes) {
            $newAttributes = $this->attributes;
            foreach ($changedAttributes as $attributeName => $attributeValue) {
                if(in_array($attributeName, $this->_logged_attrubutes)) {
                    if($attributeValue != $newAttributes[$attributeName]) {
                        Log::setLog($this->id, $is_new, $attributeName, $attributeValue, $newAttributes[$attributeName], $operation_id, $is_deleted);
                    }
                }
            }
        }
    }
    public function setDeleteLog()
    {
        $operation_id = uniqid();
        $attributes = $this->attributes;
        foreach ($attributes as $attributeName => $attributeValue) {
            if(in_array($attributeName, $this->_logged_attrubutes)) {
                Log::setLog($this->id, false, $attributeName, $attributeValue, $attributeValue, $operation_id, true);
            }
        }
    }
    public function getRepeatString()
    {
        $str = '';
        if($this->repeat_day_begin) {
            $str .= 'С '.$this->repeat_day_begin;
        }
        if($this->repeat_type) {
            $str .= ' '.$this->getRepeatTypeName();
        }
        if($this->repeat_type_values) {
            $str .= ' '.$this->getRepeatValuesNames();
        }
        return $str;
    }
    public function getRepeatTypeName()
    {
        $repeat_data = $this->getRepeatData();
        if($this->repeat_type && array_key_exists($this->repeat_type, $repeat_data)) {
            return $repeat_data[$this->repeat_type]['name'];
        }
        return false;
    }
    public function getRepeatValuesNames()
    {
        $repeat_data = $this->getRepeatData();
        if($this->repeat_type && !empty($this->repeat_type_values) && array_key_exists($this->repeat_type, $repeat_data)) {
            $str = '';
            foreach($this->repeat_type_values as $typeValue) {
                $str .= $repeat_data[$this->repeat_type]['values'][$typeValue].' ';
            }
            return $str;
        }
        return false;
    }
    public function setRepeats()
    {
        //if(!$this->repeat_id) return false;
        //file_put_contents('info-log.txt', 'model - '.print_r($this, true)."\n", FILE_APPEND);
        $repeat_data = $this->getRepeatData();
        $date_begin = strtotime($this->repeat_day_begin);
        $date_end = mktime(0, 0, 0, 12, 31, date('Y'));
        $repeat_id = mt_rand(100000,1000000);
        foreach($repeat_data as $type => $typeValues) {
            if($this->repeat_type == $type) {
                $this->repeat_id = $repeat_id;
                switch ($type) {
                    case self::REPEAT_EVERY_DAY : {
                        $diff = 3600 * 24;
                        while ($date_begin < $date_end) {
                            $date_begin = $date_begin + $diff;

                            if($this->repeat_type_values) {
                                $week_day = date('N', $date_begin);
                                if(!in_array($week_day, $this->repeat_type_values)) {
                                    continue;
                                }
                            }
                            if($this->date != $date_begin) {
                                $timetable = new Timetable();
                                $timetable->attributes = $this->attributes;
                                $timetable->date = $date_begin;
                                if(!$timetable->save()) {
                                    file_put_contents('info-log.txt', print_r($timetable->errors, true)."\n", FILE_APPEND);
                                }
                            }
                        }
                    }
                    break;
                    case self::REPEAT_EVERY_WEEK : {
                        $diff = 3600 * 24 * 7;
                        $count = 1;
                        while ($date_begin < $date_end) {
                            if($this->repeat_type_values) {
                                $repeat_type = implode(',', $this->repeat_type_values);
                                $repeat_type = (integer) $repeat_type;

                                if(!($count % $repeat_type)) {
                                    $timetable = new Timetable();
                                    $timetable->attributes = $this->attributes;
                                    $timetable->date = $date_begin;
                                    if(!$timetable->save()) {
                                        file_put_contents('info-log.txt', 'errors - '.print_r($timetable->errors, true)."\n", FILE_APPEND);
                                    }
                                }
                            }
                            $date_begin = $date_begin + $diff;
                            $count++;
                        }
                    }
                    break;
                    case self::REPEAT_EVERY_MONTH : {
                        while ($date_begin < $date_end) {
                            $date_begin_day = date('j', $date_begin);
                            $date_begin_month = date('n', $date_begin);
                            $date_begin_year = date('Y', $date_begin);

                            $date_begin = mktime(0,0,0,($date_begin_month + 1), $date_begin_day, $date_begin_year);

                            $monthNumber = date('n', $date_begin);
                            if(in_array($monthNumber, $this->repeat_type_values)) {
                                if($this->date != $date_begin) {
                                    $timetable = new Timetable();
                                    $timetable->attributes = $this->attributes;
                                    $timetable->date = $date_begin;
                                    if(!$timetable->save()) {
                                        file_put_contents('info-log.txt', 'errors - '.print_r($timetable->errors, true)."\n", FILE_APPEND);
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }
        }
    }



}
