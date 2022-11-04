<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\Cookie;

/**
 * This is the model class for table "user_cook".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $value
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserCook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cook';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'value'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function setValue()
    {
        $cookValue = mt_rand(10000, 100000);
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name' => 'user_cook_id',
            'value' => $cookValue,
        ]));

        if(!$model = self::findOne(['user_id' => Yii::$app->user->id])) {
            $model = new self();
            $model->user_id = Yii::$app->user->id;
        }
        $model->value = $cookValue;
        return $model->save();
    }

    public function isValue()
    {
        $cookies = Yii::$app->request->cookies;
        $cookValue = $cookies->getValue('user_cook_id', 0);
        return self::find()->where(['user_id' => Yii::$app->user->id, 'value' => $cookValue])->exists();
    }
}
