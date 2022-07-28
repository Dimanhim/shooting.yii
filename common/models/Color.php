<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "colors".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property string $value
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Color extends BaseModel
{
    const COLOR_ID_1 = 7;                         // для единичного клиента
    const COLOR_ID_2 = 8;                        // для постоянного клиента
    const COLOR_TIMETABLE_DEFAULT = 9;          // для заявок по умолчанию

    const GROUP_PLACE = 1;
    const GROUP_TIMETABLE_RECORD = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'background', 'group_id'], 'required'],
            [['name', 'background', 'border', 'text'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['group_id'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'group_id' => 'Группа',
            'name' => 'Название',
            'description' => 'Описание',
            'background' => 'Фон',
            'border' => 'Граница',
            'text' => 'Текст',

        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }

    public static function getGroups()
    {
        return [
            self::GROUP_PLACE               => 'Стрельбища',
            self::GROUP_TIMETABLE_RECORD    => 'Расписание',
        ];
    }

    public function getGroupName()
    {
        if($this->group_id && array_key_exists($this->group_id, self::getGroups())) {
            return self::getGroups()[$this->group_id];
        }
        return false;
    }

    /**
     * @param $background
     * @param null $border
     * @param null $color
     * @param null $block
     * @return string
     */
    public function getViewBlock($background)
    {
        if($background) {
            return '<div class="color-block" style="background: '.$background.'"></div>';
        }
        return '<div class="color-block">Не задано</div>';
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}
