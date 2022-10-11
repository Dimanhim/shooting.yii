<?php

namespace common\models;

use common\components\Helper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "places".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $street_id
 * @property string $name
 * @property string|null $description
 * @property string|null $short_description
 * @property int|null $price
 * @property int|null $price_2
 * @property int|null $price_3
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Place extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'places';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'short_description'], 'string'],
            [['price', 'price_2', 'price_3', 'color_id'], 'integer'],
            [['street_id', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'id' => 'ID',
            'street_id' => 'Улица',
            'name' => 'Название',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
            'price' => 'Стоимость',
            'price_2' => 'Price  2',
            'price_3' => 'Price  3',
            'color_id' => 'Цвет',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }

    public function getAdress()
    {
        return $this->hasOne(Street::className(), ['id' => 'street_id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }

    /**
     * @return array
     */
    public function getColumnValues($id, $date)
    {
        $model = new BaseModel();
        $result = [];

        if(
            $timetables = Timetable::find()
                ->where([
                    //'date' => $model->getConfig()[$this->getCacheName('date_timestamp')],
                    'date' => strtotime($date),
                    'place_id' => $id,
                ])
                ->all()
        ) {
            foreach($timetables as $timetable) {
                $result[] = [
                    'id' => $timetable->id,
                    'name' => $timetable->name,
                    'phone' => $timetable->phone,
                    'description' => $timetable->description,
                    'short_description' => $timetable->short_description,
                    'date' => $timetable->date,
                    'time_from' => $timetable->time_from,
                    'time_to' => $timetable->time_to,
                    'styles' => $timetable->getItemStyle(),
                    'qty' => $timetable->qty,
                    //'serviceName' => $timetable->service ? $timetable->service->name : '',
                    'serviceName' => $timetable->service_name,
                    'infinity' => $timetable->repeat_id,
                ];
            }
        }
        return $result;
    }

    /**
     * @param $time
     * @param $columnValues
     * @return string
     */
    public function getColumnValue($time, $columnValues)
    {
        $time = Helper::formatTimeFromHours($time);
        $str = '';
        if(!empty($columnValues)) {
            foreach($columnValues as $columnValue) {
                if($columnValue['time_from'] >= $time && $columnValue['time_from'] <= $time) {
                    $str .= Yii::$app->controller->renderPartial('//site/_record', [
                        'time' => $time,
                        'column' => $columnValue,
                        'model' => $this,
                    ]);
                }
            }
        }
        return $str;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }
}
