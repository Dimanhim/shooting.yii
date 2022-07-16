<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "role_rights".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $role_id
 * @property int|null $right_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class RoleRight extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role_rights';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'right_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributeLabels = [
            'role_id' => 'Роль',
            'right_id' => 'Права',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }
}
