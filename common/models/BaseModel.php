<?php

namespace common\models;

use common\components\Helper;
use PHPUnit\TextUI\Help;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    const CACHE_DURATION = 3600 * 24 * 7;
    const COLUMN_ROWS = 6;

    public $_user;

    protected $_config = [];

    protected $_default_values = [
        'places' => [],
        'places_date' => [],
        'temp_places' => [],
    ];

    /**
     * BaseModel constructor.
     */
    public function __construct()
    {
        /** DATE */
        $this->getCacheDate();

        /** PLACES */
        $this->getCachePlaces();


        /** TEMP PLACES 0*/
        //$this->getCacheTempPlaces();

        $this->_user = User::findOne(Yii::$app->user->id);

    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }


    /**
     * @param null $background
     * @param null $border
     * @param null $text
     * @return string
     */
    public function getStyles($background = null, $border = null, $text = null)
    {
        $str = '';
        if($background) $str .= 'background: '.$background.';';
        if($border) $str .= 'border-left: 3px solid '.$border.';';
        if($text) $str .= 'color: '.$text.';';
        return $str;
    }

    /**
     * @param bool $timestamp
     * @return float|int|mixed
     */
    public function getDateCash($timestamp = false)
    {
        return $timestamp ? $this->_config[$this->getCacheName('date_timestamp')] : $this->_config[$this->getCacheName('date')];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['unique_id', 'is_active', 'deleted', 'position', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'unique_id' => 'Уникальный ID',
            'is_active' => 'Активность',
            'deleted' => 'Удален',
            'position' => 'Сортировка',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord) {
            $this->unique_id = uniqid();
            $this->is_active = 1;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * @return string
     */
    public function getColorStyles()
    {
        if($this->color) {
            $styles = '';
            if($this->color->background) {
                $styles .= 'background: '.$this->color->background.';';
            }
            if($this->color->border) {
                $styles .= 'border-left: 3px solid '.$this->color->border.';';
            }
            if($this->color->color) {
                $styles .= 'color: '.$this->color->color.';';
            }
            return $styles;
        }
        return 'background: #fff; color: #000;';
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        if($this->color && $this->color->background) {
            return $this->color->background;
        }
        return 'background: #fff;';
    }


    /**
     * @return string
     */
    public function getColumns()
    {
        $config = $this->_config;

        foreach($config[$this->getCacheName('places')] as $v) {
            if($v) {
                $m = Place::findOne($v);
                $cacheDate = $m->getCacheDate();
                $result[] = [
                    'id' => $m->id,
                    'name' => $m->name,
                    'background_color' => $m->getBackgroundColor(),
                    'styles' => $m->getColorStyles(),
                    'date' => $cacheDate['date'],
                    'date_timestamp' => $cacheDate['date_timestamp'],
                    'default' => true,
                ];
            }
        }
        /*if($tempPlaces = $config[$this->getCacheName('temp_places')]) {
            foreach($tempPlaces as $date => $value) {
                if($value) {
                    foreach($value as $val) {
                        $m = Place::findOne($val);
                        $result[] =[
                            'id' => $m->id,
                            'name' => $m->name,
                            'background_color' => $m->getBackgroundColor(),
                            'styles' => $m->getColorStyles(),
                            'date' => $date,
                            'date_timestamp' => strtotime($date),
                            'default' => false,
                        ];
                    }
                }
            }
        }*/

        $html = '';
        $countItem = 0;
        if($result) {
            foreach($result as $value) {
                $html .= Yii::$app->controller->renderPartial('//site/_column', [
                    'model' => new Place(),
                    'result' => $value,
                    'countItem' => $countItem,
                    'countResult' => count($result),
                ]);
                $countItem++;
            }
        }
        return $html;
    }

    /**
     * @param $placeName
     * @param $placeValue
     * @return bool
     */
    public function isShowed($placeName, $placeValue)
    {
        $config = $this->_config;
        if(array_key_exists($this->getCacheName($placeName), $config)) {
            if(is_array($config[$this->getCacheName($placeName)])) {
                return in_array($placeValue, $config[$this->getCacheName($placeName)]);
            }
            return $placeValue == $config[$this->getCacheName($placeName)];
        }
    }



    /**
    CACHE
     */
    public function setCacheDate($date) {
        $cacheNameDate = $this->getCacheName('date');
        $cacheNameDateTimestamp = $this->getCacheName('date_timestamp');
        $this->setCache($cacheNameDate, $date);
        $this->_config[$cacheNameDate] = $date;
        $this->_config[$cacheNameDateTimestamp] = strtotime($date);
    }
    public function getCacheDate() {
        $cacheNameDate = $this->getCacheName('date');
        $cacheNameDateTimestamp = $this->getCacheName('date_timestamp');
        if(!$this->cacheExists($cacheNameDate)) {
            $this->setCache($cacheNameDate, date('d.m.Y'));
        }
        $date = $this->getCache($cacheNameDate);
        $this->_config[$cacheNameDate] = $date;
        $this->_config[$cacheNameDateTimestamp] = strtotime($date);
        return [
            'date' => $this->_config[$cacheNameDate],
            'date_timestamp' => $this->_config[$cacheNameDateTimestamp],
        ];
    }

    public function setCachePlaces($places) {
        $cacheNamePlaces = $this->getCacheName('places');
        $placesString = Helper::getStringFromArray($places);
        $this->setCache($cacheNamePlaces, $placesString);
        $this->_config[$cacheNamePlaces] = $places;
    }
    public function getCachePlaces() {
        $cacheNamePlaces = $this->getCacheName('places');
        if(!$this->cacheExists($cacheNamePlaces)) {
            $this->setCache($cacheNamePlaces, Helper::getStringFromArray($this->_default_values['places']));
        }
        $places = $this->getCache($cacheNamePlaces);
        $this->_config[$cacheNamePlaces] = Helper::getArrayFromString($places);
        return $this->_config[$cacheNamePlaces];
    }

    /**
    $array = [
     * ]
     */
    /*public function setCacheTempPlaces($placesDatesArray) {
        $cacheNamePlaces = $this->getCacheName('temp_places');
        $placesString = json_encode($placesDatesArray);
        $this->setCache($cacheNamePlaces, $placesString);
        $this->_config[$cacheNamePlaces] = $placesDatesArray;
    }
    public function getCacheTempPlaces() {
        $cacheNamePlaces = $this->getCacheName('temp_places');
        if(!$this->cacheExists($cacheNamePlaces)) {
            $this->setCache($cacheNamePlaces, Helper::getStringFromArray($this->_default_values['places']));
        }
        $places = $this->getCache($cacheNamePlaces);
        $this->_config[$cacheNamePlaces] = json_decode($places, true);
    }*/






    public function getCacheName($name) {
        return $name.'_'.Yii::$app->user->id;
    }
    private function setCache($name, $value) {
        Yii::$app->cache->set($name, $value, self::CACHE_DURATION);
    }
    private function cacheExists($name) {
        return Yii::$app->cache->exists($name);
    }
    private function getCache($name) {
        return Yii::$app->cache->get($name);
    }
    private function deleteCash($name)
    {
        return Yii::$app->cache->delete($name);
    }






































    public $_styles = [
        'titles' => [
            0 => '#5F95E9;',
            1 => '#5F95E9;',
            2 => '#183863;',
            3 => '#183863;',
        ],
        'columns' => [
            0 => 'border-left: 3px solid #A9827E; background: #EDD3D1;',
            1 => 'border-left: 3px solid #5F7086; background: #A7B3C3;',
            2 => 'border-left: 3px solid #84A777; background: #D7EECE;',
            3 => 'border-left: 3px solid #80AB6E; background: #98CF7E;',
        ],
    ];
    public $_text = [
        'titles' => [
            0 => 'Марка 20м',
            1 => 'Марка 30м',
            2 => 'Ботанический сад 20м',
            3 => 'Ботанический сад 30м',
        ],
        'columns' => [
            0 => 'Lorem ipsum dolor',
            1 => 'Lorem ipsum dolor sit amet',
            2 => 'Lorem ipsum dolor sit amet, consectetur',
            3 => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.',
        ],
    ];

    public function getRandomTitleStyle()
    {
        $index = mt_rand(0,3);
        return $this->_styles['titles'][$index];
    }
    public function getRandomColumnStyle()
    {
        $index = mt_rand(0,3);
        return $this->_styles['columns'][$index];
    }
    public function getRandomTitle()
    {
        $index = mt_rand(0,3);
        return $this->_text['titles'][$index];
    }
    public function getRandomText()
    {
        $index = mt_rand(0,3);
        return $this->_text['columns'][$index];
    }
}
