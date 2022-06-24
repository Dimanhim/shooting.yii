<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $id
 * @property string $unique_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Client extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'email' => 'E-mail',
            'phone' => 'Телефон',
        ];
    }
}
