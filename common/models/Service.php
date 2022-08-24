<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property string|null $short_description
 * @property string|null $description
 * @property int|null $price
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Service extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['short_description', 'description'], 'string'],
            [['price'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'name' => 'Название',
            'short_description' => 'Короткое описание',
            'description' => 'Описание',
            'price' => 'Стоимость',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }

    public static function getList()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
