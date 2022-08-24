<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "trainers".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Trainer extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trainers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['short_description', 'description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'name' => 'Имя',
            'short_description' => 'Короткое описание',
            'description' => 'Описание',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }
}
