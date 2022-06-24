<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "streets".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property string|null $description
 * @property string|null $short_description
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Street extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'streets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'short_description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'short_description' => 'Короткое описание',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['street_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }
}
