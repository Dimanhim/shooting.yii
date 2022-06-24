<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public $_config = [
        'places' => [
            1,2,3,4
        ],
        'date' => '',
    ];
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
            0 => 'Lorem ipsum dolor sit amet',
            1 => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. At, cum dicta',
            2 => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque et harum id necessitatibus nemo neque officia quas repellendus, sunt suscipit. Eius harum ',
            3 => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque et harum id necessitatibus nemo neque officia quas repellendus, sunt suscipit. Eius harum iure voluptatibus! Ducimus eveniet fuga officiis quod velit.',
        ],
    ];

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

    public function beforeSave($insert)
    {
        if($this->isNewRecord) {
            $this->unique_id = uniqid();
            $this->is_active = 1;
        }
        return parent::beforeSave($insert);
    }

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

    public function getBackgroundColor()
    {
        if($this->color && $this->color->background) {
            return $this->color->background;
        }
        return 'background: #fff;';
    }

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
    public function getTimesArray()
    {
        $hourBegin = 9;
        $countHours = 14;

        $result = [];

        for($i = 0; $i < $countHours; $i++) {
            $result[] = $hourBegin + $i;
        }
        return $result;
    }

    public function getColumns()
    {
        if(!$model = Place::find()->where(['in', 'id', $this->_config['places']])->all()) {
            $model = $this;
        }
        return Yii::$app->controller->renderPartial('//site/columns', [
            'model' => $model,
        ]);
    }
}
