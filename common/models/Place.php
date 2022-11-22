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
                    'place_id' => $timetable->place_id,
                    //'styles' => $timetable->getItemStyle(),
                    'styles' => $timetable->getItemStyle(),
                    'height' => $timetable->getItemHeight(),
                    'rows' => $timetable->getItemRows(),
                    'qty' => $timetable->qty,
                    'serviceName' => $timetable->service_name,
                    'infinity' => $timetable->repeat_id,
                ];
            }
        }

        return $this->sortByHeight($result) ;
    }

    public function sortByHeight($records)
    {
        usort($records, function($a, $b) {
            return $b['height'] - $a['height'];
        });
        return $records;
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

    public function getRecordsArray()
    {
        $place_id = 1;
        $place = Place::findOne($place_id);
        $timetablesArray = [];
        $timetablesData = [];
        $countRows = 0;
        $timetables = Timetable::find()->where(['date' => $this->getDateCash(true), 'place_id' => $place->id])->orderBy(['time_from' => SORT_ASC])->all();
        if($timetables) {

            foreach($timetables as $timetable) {
                $timetablesData[] = [
                    'id' => $timetable->id,
                    'name' => $timetable->name,
                    'phone' => $timetable->phone,
                    'description' => $timetable->description,
                    'short_description' => $timetable->short_description,
                    'date' => $timetable->date,
                    'time_from' => $timetable->time_from,
                    'time_to' => $timetable->time_to,
                    //'styles' => $timetable->getItemStyle(),
                    'styles' => $timetable->getItemStyle(),
                    'height' => $timetable->getItemHeight(),
                    'rows' => $timetable->getItemRows(),
                    'qty' => $timetable->qty,
                    'serviceName' => $timetable->service_name,
                    'infinity' => $timetable->repeat_id,
                ];
            }
            if($timetablesData) {
                foreach($timetablesData as $timetableItem) {
                    $timetablesArray[$timetableItem['time_from']][] = $timetableItem;
                }
            }












            foreach($timetablesArray as $timetableItem) {
                if(count($timetableItem) > $countRows) $countRows = count($timetableItem);
            }
            // определили количество колонок $countRows
            if($countRows) {
                $resultArray = [];
                $i = 1;
                foreach($timetablesArray as $timetableTime => $timetableValue) {
                    if(!empty($timetableValue)) {
                        foreach($timetableValue as $key => $eachRecord) {
                            $resultArray[$timetableTime][$key] = $eachRecord;
                            $resultArray[$timetableTime][$key]['type'] = 'full';

                        }
                        if(count($timetableValue) < $countRows) {
                            for($j = 0; $j < ($countRows - count($timetableValue)); $j++) {
                                $resultArray[$timetableTime][$key + $j + 1] = [];
                                $resultArray[$timetableTime][$key + $j + 1]['type'] = 'empty';
                            }
                        }
                    }
                    $i++;
                }
            }
        }
        /*echo "<pre>";
        print_r($resultArray);
        echo "</pre>";
        exit;*/
        return $resultArray;






        // определили количество колонок $countRows
        echo "<pre>";
        print_r($resultArray);
        echo "</pre>";
        exit;


















        $records = [
            '6' => [
                0 => [
                    'type' => 'full',
                    'id' => 217,
                    'name' => 'Dimas',
                    'qty' => '2 взрослых',
                    'height' => 158,
                    'rows' => 4,
                    'style' => 'background: rgb(167, 179, 195); border-left: 3px solid rgb(95, 112, 134); height: 158px; z-index:110; position: relative;',
                ],
                1 => [
                    'type' => 'full',
                    'id' => 111,
                    'name' => 'Dimas 1',
                    'qty' => '3 взрослых',
                    'height' => 78,
                    'rows' => 2,
                    'style' => 'background: rgb(167, 179, 195); border-left: 3px solid rgb(95, 112, 134); height: 78px; z-index:100; position: relative;',
                ],
                2 => [
                    'type' => 'full',
                    'id' => 222,
                    'name' => 'Dimas 2',
                    'qty' => '4 взрослых',
                    'height' => 78,
                    'rows' => 2,
                    'style' => 'background: rgb(167, 179, 195); border-left: 3px solid rgb(95, 112, 134); height: 78px; z-index:100; position: relative;',
                ],
            ],
            '6:30' => [
                0 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                1 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                2 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
            ],
            '7' => [
                0 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                1 => [
                    'type' => 'full',
                    'id' => 111,
                    'name' => 'Dimas 1',
                    'qty' => '3 взрослых',
                    'height' => 78,
                    'rows' => 2,
                    'style' => 'background: rgb(167, 179, 195); border-left: 3px solid rgb(95, 112, 134); height: 78px; z-index:100; position: relative;',
                ],
                2 => [
                    'type' => 'full',
                    'id' => 222,
                    'name' => 'Dimas 2',
                    'qty' => '4 взрослых',
                    'height' => 78,
                    'rows' => 2,
                    'style' => 'background: rgb(167, 179, 195); border-left: 3px solid rgb(95, 112, 134); height: 78px; z-index:100; position: relative;',
                ],
            ],
            '7:30' => [
                0 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                1 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                2 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
            ],
            '8' => [
                0 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                1 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                2 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
            ],
            '8:30' => [
                0 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                1 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
                2 => [
                    'type' => 'empty',
                    'name' => '',
                    'qty' => '',
                    'height' => 40,
                    'rows' => 1,
                    'style' => '',
                ],
            ],
        ];
        return $records;
    }


    public static function eachPlaceDateArray()
    {
        return [
            0 => [
                'id' => 84,
                'name' => 'Dimas m 20 9:00-11:30',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 32400,
                'time_to' => 41400,
                'styles' => 'background: #EDD3D1;border-left: 3px solid #A9827E;height: 200px;',
                'height' => 200,
                'rows' => 5,
                'qty' => '101010',
                'serviceName' => '',
                'infinity' => '',
            ],
            1 => [
                'id' => 81,
                'name' => 'Dimas m 20 8:00-10:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 28800,
                'time_to' => 36000,
                'styles' => 'background: #D7EECE;height: 160px;',
                'height' => 160,
                'rows' => 4,
                'qty' => '777',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            2 => [
                'id' => 76,
                'name' => 'Dimas m20 7:00-9:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 25200,
                'time_to' => 32400,
                'styles' => 'background: #EDD3D1;border-left: 3px solid #A9827E;height: 160px;',
                'height' => 160,
                'rows' => 4,
                'qty' => '222',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            3 => [
                'id' => 86,
                'name' => 'Dimas m 20 8:00-10:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 28800,
                'time_to' => 36000,
                'styles' => 'background: #A7B3C3;border-left: 3px solid #5F7086;height: 160px;',
                'height' => 160,
                'rows' => 4,
                'qty' => '121212',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            4 => [
                'id' => 80,
                'name' => 'Dimas m 20 11:00-12:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 39600,
                'time_to' => 45000,
                'styles' => 'background: #D7EECE;height: 120px;',
                'height' => 120,
                'rows' => 3,
                'qty' => '667',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            5 => [
                'id' => 75,
                'name' => 'Dimas m 20 6:00-7:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 21600,
                'time_to' => 25200,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '111 chel',
                'serviceName' => '',
                'infinity' => '',
            ],
            6 => [
                'id' => 77,
                'name' => 'Dimas m 20 8:00-9:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 28800,
                'time_to' => 32400,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '333',
                'serviceName' => '',
                'infinity' => '',
            ],
            7 => [
                'id' => 78,
                'name' => 'Dimas m 20 9:00-10:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 32400,
                'time_to' => 36000,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '444',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            8 => [
                'id' => 79,
                'name' => 'Dimas m 20 10:00-11:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 36000,
                'time_to' => 39600,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '555',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            9 => [
                'id' => 82,
                'name' => 'Dimas m 20 9:00-10:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 32400,
                'time_to' => 36000,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '888',
                'serviceName' => 'ser',
                'infinity' => '',
            ],
            10 => [
                'id' => 83,
                'name' => 'Dimas m 20 7:00-80:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 25200,
                'time_to' => 28800,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '999',
                'serviceName' => '',
                'infinity' => '',
            ],
            11 => [
                'id' => 85,
                'name' => 'Dimas m 20 11:00-12:00',
                'phone' => '',
                'description' => '',
                'short_description' => '',
                'date' => 1668373200,
                'time_from' => 39600,
                'time_to' => 43200,
                'styles' => 'background: #D7EECE;height: 80px;',
                'height' => 80,
                'rows' => 2,
                'qty' => '111111',
                'serviceName' => '',
                'infinity' => '',
            ],

        ];
    }

    public static function donePlaceDateArray($newTestArray, $date_timestamp, $place_id)
    {
        // задаем начальную вложенность массива
        // он будет меняться
        $rows = 0;

        // сам результирующий возвращаемый массив
        /**
        будет в формате
         *   0 => [           номер колонки
         *    36000 => [      время
         *        id => 85    значения
         *   ]
         * ]
         */
        $resultArray = [];

        // массив временных промежутков [сек] => час:мин
        $timesArray = Helper::getTimesSecondsArray();

        // 37 временных промежутков
        $timeGaps = count($timesArray);

        // макс. высота - 1480px;
        $totalHeight = $timeGaps * Timetable::BASE_ROW_hEIGHT;



        // берем массив расписания на конкретный корт на конкретную дату
        //$timetableArray = self::eachPlaceDateArray();
        $timetableArray = $newTestArray;


        //пустой массив не выводит


        // сортируем его по времени записи
        if($timetableArray) {
            usort($timetableArray, function($a, $b) {
                return ($a['time_from'] < $b['time_from']) ? -1 : 1;
            });
        }


        //$resultArray
        $iterations = 0;
        if($timetableArray) {
            while(!empty($timetableArray)) {
                if($iterations > 10) {
                    echo "<pre>";
                    print_r('пиздец, завис');
                    echo "</pre>";
                    break;
                }
                // остаток от первоначального массива
                $eachIterationResult = self::addValuesToResult($timetableArray, $resultArray, $timesArray, $rows, $date_timestamp, $place_id);
                $rows++;
                $timetableArray = $eachIterationResult['timetableArray'];
                $resultArray = $eachIterationResult['resultArray'];
                $iterations++;
            }
        }
        else {
            $resultArray[$rows] = self::getEmptyValues($timesArray, $date_timestamp, $place_id);
        }


        // далее если есть еще записи в расписании, добавляем к rows единицу и проводим заново операцию выше.
        // нужно ее выделить в отдельный метод
        return $resultArray;

        /*echo "<pre>";
        print_r('$resultArray - ');
        print_r($resultArray);
        echo "</pre>";

        echo "<pre>";
        print_r('$timetableArray - ');
        print_r($timetableArray);
        echo "</pre>";
        exit;*/
    }
    public static function getEmptyValues($timesArray, $date_timestamp, $place_id)
    {
        $resultArray = [];
        // удаляет значения массива, по ключю потом не находит
        // делаем самое начальное наполнение РЕЗ массива
        foreach($timesArray as $seconds => $timeString) {
            $resultArray[$seconds] = ['fill' => 'empty', 'time' => $seconds, 'date' => $date_timestamp, 'place_id' => $place_id];
        }
        return $resultArray;
    }

    public static function addValuesToResult($timetableArray, $resultArray, $timesArray, $rows, $date_timestamp, $place_id)
    {
        // удаляет значения массива, по ключю потом не находит
        // делаем самое начальное наполнение РЕЗ массива
        $resultArray[$rows] = self::getEmptyValues($timesArray, $date_timestamp, $place_id);
        foreach($timetableArray as $key => $timetableValue) {
            //for($i = 0; $i < count($timetableArray); $i++) {
            // если в РЕЗ массиве есть значение на это время, то добавляем сюда значения из расписания
            // сюда же нужно добавить условие для высоты всего блока - т.е. вычисляем количество занятых ячеек по высоте и запрещаем добавлять в них

            // собственно, само добавление
            $item_time_from = $timetableValue['time_from'];
            if($resultArray[$rows][$timetableValue['time_from']]['fill'] == 'empty' ) {


                $item_height = $timetableValue['height'];
                $resultArray[$rows][$item_time_from] += $timetableValue;

                $height_diff = $item_height / Timetable::BASE_ROW_hEIGHT;
                for($j = 0; $j < $height_diff; $j++) {
                    if(isset($resultArray[$rows][$item_time_from + Timetable::DIFF_COUNT_SECONDS * $j])) {
                        if($j == 0) {
                            $resultArray[$rows][$item_time_from + Timetable::DIFF_COUNT_SECONDS * $j]['fill'] = 'buzy';
                        }
                        else {
                            unset($resultArray[$rows][$item_time_from + Timetable::DIFF_COUNT_SECONDS * $j]);
                        }
                    }
                }


                // и удаляем эту запись из массива расписания
                unset($timetableArray[$key]);
            }
        }
        return ['resultArray' => $resultArray, 'timetableArray' => $timetableArray];
    }
}
