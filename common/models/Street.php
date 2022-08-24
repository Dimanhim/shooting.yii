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
        if($cachePlaces = $this->getCachePlaces()) {
            $resultFirst = [];
            foreach($cachePlaces as $cachePlaceId) {
                if($place = Place::find()->where(['id' => $cachePlaceId, 'street_id' => $this->id])->one()) {
                    $resultFirst[] = $place;
                }
            }
            //$resultFirst = Place::find()->where(['street_id' => $this->id])->andWhere(['in', 'id', $cachePlaces])->all();
            $resultSecond = Place::find()->where(['street_id' => $this->id])->andWhere(['not in', 'id', $cachePlaces])->all();
            return array_merge($resultFirst, $resultSecond);
        }
        return $this->hasMany(Place::className(), ['street_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }

    public function isShowedPlaces()
    {
        if($places = Place::find()->select('id')->where(['street_id' => $this->id])->all()) {
            foreach($places as $place) {
                if($place->isShowed('places', $place->id)) return true;
            }
        }
        return false;
    }
}
