<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property int $timetable_id
 * @property string $attribute
 * @property string $change_from
 * @property string $change_to
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
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
            [['timetable_id', 'attribute', 'change_to', 'user_id'], 'required'],
            [['timetable_id', 'user_id', 'is_new'], 'integer'],
            [['attribute', 'change_from', 'change_to', 'operation_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation_id' => 'Operation ID',
            'is_new' => 'Создание',
            'timetable_id' => 'Timetable ID',
            'attribute' => 'Attribute',
            'change_from' => 'Change From',
            'change_to' => 'Change To',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimetable()
    {
        return $this->hasOne(Timetable::className(), ['id' => 'timetable_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function setLog($timetable_id, $is_new, $attributeName, $changeFrom, $changeTo, $operation_id)
    {
        $log = new self();
        $log->timetable_id = $timetable_id;
        $log->is_new = (integer) $is_new;
        $log->attribute = (string) $attributeName;
        $log->change_from = $changeFrom ? (string) $changeFrom : '';
        $log->change_to = (string) $changeTo;
        $log->user_id = Yii::$app->user->id;
        $log->operation_id = $operation_id;
        if(!$log->save()) {
            Yii::info($log->errors);
        }

    }

    public function getAttributeName()
    {
        $timetable = new Timetable();
        return $timetable->attributeLabels()[$this->attribute];
    }

    public static function groupLogs($logs)
    {
        if($logs) {
            $unique_id = [];
            $models = [];
            foreach($logs as $log) {
                if(in_array($log->operation_id, $unique_id)) {
                    $models[$log->operation_id][] = $log;
                }
                else {
                    $models[$log->operation_id] = [$log];
                    $unique_id[] = $log->operation_id;
                }
            }
        }

        if($models) {
            $logs = [];
            foreach($models as $key => $model) {
                $placeResult = self::getPlaceName($model);
                $logs[] = [
                    'datetime' => date('d.m.Y H:i', $model[0]->created_at),
                    'placeName' => $placeResult['name'],
                    'string' => $placeResult['string'],
                    'user' => $model[0]->user ? $model[0]->user->name : '',
                ];
            }
        }
        return $logs;
    }

    public function getChangefromName()
    {
        if($this->attribute == 'place_id') {
            return ($place = Place::findOne([$this->change_from])) ? $place->name : '';
        }
        if($this->attribute == 'service_id') {
            return ($service = Service::findOne([$this->change_from])) ? $service->name : '';
        }
        if($this->attribute == 'date') {
            return $this->change_from ? date('d.m.Y', $this->change_from) : '';
        }
        if($this->attribute == 'time_from') {
            return $this->change_from ? Helper::getTimeAsString($this->change_from) : '';
        }
        if($this->attribute == 'time_to') {
            return $this->change_from ? Helper::getTimeAsString($this->change_from) : '';
        }
        return $this->change_from;
    }
    public function getChangetoName()
    {
        if($this->attribute == 'place_id') {
            return ($place = Place::findOne([$this->change_to])) ? $place->name : '';
        }
        if($this->attribute == 'service_id') {
            return ($service = Service::findOne([$this->change_to])) ? $service->name : '';
        }
        if($this->attribute == 'date') {
            return $this->change_to ? date('d.m.Y', $this->change_to) : '';
        }
        if($this->attribute == 'time_from') {
            return $this->change_to ? Helper::getTimeAsString($this->change_to) : '';
        }
        if($this->attribute == 'time_to') {
            return $this->change_to ? Helper::getTimeAsString($this->change_to) : '';
        }
        return $this->change_to;
    }

    public function getEachLogString()
    {
        $str = '';
        $str .= $this->getAttributeName(). ': ';
        $str .= 'с <b>' . $this->getChangefromName().'</b>';
        $str .= ' на <b>' . $this->getChangetoName(). '</b><br>';
        return $str;
    }

    /**
     * @return array
     */
    public static function getPlacesArray()
    {
        return [
            [
                'placeId' => 'new_record',
                'place_name' => 'Добавлена новая запись',
                'attributes' => [
                    'is_new',
                ],
            ],
            [
                'placeId' => 'drop_record',
                'place_name' => 'Запись перемещена на другое стрельбище на другое время',
                'attributes' => [
                    'place_id', 'time_from', 'time_to'
                ],
            ],
            [
                'placeId' => 'drop_record',
                'place_name' => 'Запись перемещена на другое время',
                'attributes' => [
                    'time_from', 'time_to'
                ],
            ],
            [
                'placeId' => 'attribute_default',
                'place_name' => 'Изменен номер телефона',
                'attributes' => [
                    'phone',
                ],
            ],
            [
                'placeId' => 'attribute_date',
                'place_name' => 'Изменена дата',
                'attributes' => [
                    'date',
                ],
            ],
            [
                'placeId' => 'attribute_time',
                'place_name' => 'Изменено время начала',
                'attributes' => [
                    'time_from',
                ],
            ],
            [
                'placeId' => 'attribute_time',
                'place_name' => 'Изменено время окончания',
                'attributes' => [
                    'time_to',
                ],
            ],
            [
                'placeId' => 'attribute_service',
                'place_name' => 'Изменена услуга',
                'attributes' => [
                    'service_id',
                ],
            ],
            [
                'placeId' => 'attribute_default',
                'place_name' => 'Изменено описание',
                'attributes' => [
                    'description',
                ],
            ],
            [
                'placeId' => 'attribute_place',
                'place_name' => 'Запись перемещена на другое стрельбище',
                'attributes' => [
                    'place_id',
                ],
            ],
        ];
    }

    public static function getPlaceName($logsModel)
    {
        $result = [
            'name' => '',
            'string' => '',
        ];
        if($logsModel) {
            $attributes = [];
            foreach($logsModel as $logModel) {
                if($logModel->is_new) {
                    $attributes[] = 'is_new';
                }
                else {
                    $attributes[] = $logModel->attribute;
                }
            }
            foreach(self::getPlacesArray() as $place) {
                if(empty(array_diff($place['attributes'], $attributes)) ) {
                    $result['name'] = $place['place_name'];
                    $result['string'] = self::getPlaceDescription($place['placeId'], $logsModel);
                    return $result;
                }
            }
        }
        return $result;
    }

    public static function getPlaceDescription($placeId, $logsModel)
    {
        $str = '';
        if($logsModel) {
            if($placeId == 'new_record') {
                foreach($logsModel as $logModel) {
                    $str .= $logModel->getChangetoName(). ' ';
                }
                return $str;
            }
            foreach($logsModel as $logModel) {
                $str .=$logModel->getEachLogString();
            }
        }
        return $str;
    }
}
