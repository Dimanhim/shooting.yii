<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property string|null $description
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Role extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
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
            'description' => 'Описание',
        ];
        return array_merge(parent::attributeLabels(), $attributeLabels);
    }

    /**
     * @return array
     */
    public static function rolesRights()
    {
        return [
            1 => 'Добавление записей в расписание',
            2 => 'Просмотр пользователей',
            3 => 'Редактирование раздела Места',
        ];
    }

    public static function getLinkClass($right_id)
    {
        $role_id = User::getUser()->role_id;
        return RoleRight::find()->where(['role_id' => $role_id, 'right_id' => $right_id])->exists();
    }
}
