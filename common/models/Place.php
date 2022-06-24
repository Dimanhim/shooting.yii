<?php

namespace common\models;

use Yii;

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
}
