<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use common\models\AuthAssignment;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ADMIN_ID = 1;

    public $right_ids;
    public $_right_model;
    public $role;

    public function __construct()
    {
        $this->_right_model = new UserRight();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
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
            [['username', 'password', 'role'], 'required'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['right_ids', 'role'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
            [['user_cook_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'name' => 'Имя',
            'email' => 'E-mail',
            'status' => 'Статус',
            'statusName' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'right_ids' => 'Права',
            'role' => 'Права',
        ];
    }

    public function beforeSave($insert)
    {
        $this->status = self::STATUS_ACTIVE;
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        //UserRight::deleteAll(['user_id' => $this->id]);
        /*if($this->right_ids) {
            foreach ($this->right_ids as $rightId) {
                $userRight = new UserRight();
                $userRight->user_id = $this->id;
                $userRight->right_id = $rightId;
                $userRight->save();
            }
        }*/
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->setRole;
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        //$this->right_ids = ArrayHelper::map(UserRight::find()->where(['user_id' => $this->id])->asArray()->all(), 'id', 'right_id');
        return parent::afterFind();
    }

    public function getRights()
    {
        return $this->hasMany(UserRight::className(), ['user_id' => 'id']);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_INACTIVE => 'Неактивный',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    public function getStatusName()
    {
        if($this->status and array_key_exists($this->status, self::getStatuses())) {
            return self::getStatuses()[$this->status];
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getUser()
    {
        return self::findOne(Yii::$app->user->id);
    }

    public static function usersByRole($role)
    {
        $arr = [];
        $users = self::find()->all();
        foreach($users as $user) {
            if(Yii::$app->authManager->getAssignment($role, $user->id)) $arr[] = $user;
        }
        return $arr;
    }
    public function getRolesArray()
    {
        return [
            'admin' => 'admin',
            'reception' => 'reception',
            'instructor' => 'instructor',
        ];
    }
    public function getUserRole()
    {
        if($roles = $this->rolesArray) {
            foreach($roles as $role) {
                if(Yii::$app->authManager->getAssignment($role, $this->id)) return $role;
            }
        }
        return false;
    }

    public function getSetRole()
    {
        if($assigment = AuthAssignment::findOne(['user_id' => $this->id])) {
            $assigment->item_name = $this->role;
            if($assigment->save()) return true;
        } else {
            $userRole = Yii::$app->authManager->getRole($this->role);
            Yii::$app->authManager->assign($userRole, $this->id);
            return true;
        }
    }

    public function getRight($rightName)
    {
        return UserRight::find()->where(['user_id' => $this->id, 'right_id' => $rightName])->exists();
    }
    public static function isAdmin()
    {
        return Yii::$app->authManager->getAssignment('admin', Yii::$app->user->id);
        //return Yii::$app->user->id == self::ADMIN_ID;
    }
    public static function isReception()
    {
        return Yii::$app->authManager->getAssignment('reception', Yii::$app->user->id);
    }
    public static function isInstructor()
    {
        return Yii::$app->authManager->getAssignment('instructor', Yii::$app->user->id);
    }
}
