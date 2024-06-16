<?php

namespace app\models;

use app\controllers\UsersController;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $fullname
 * @property string $password
 * @property string $role
 * @property int $created_at
 * @property int $updated_at
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public string $new_password = "";

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['username', 'fullname', 'new_password'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['fullname'], 'string', 'max' => 150],
            [['password'], 'string', 'skipOnEmpty' => true],
            [['new_password'], 'string', 'min' => 3, 'max' => 25],
            ['role', function($attribute, $params, $validator) {
                if (!array_key_exists($this[$attribute], UsersController::get_roles()))
                    $this->addError($attribute, 'Role must be from available roles.');
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('app/users', 'Username'),
            'fullname' => Yii::t('app/users', 'Fullname'),
            'new_password' => Yii::t('app/users', 'Password'),
            'role' => Yii::t('app/users', 'Role'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?Users
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password
        );
    }

    public function beforeSave($insert)
    {
        if ($insert)
        {
            $this->password = Yii::$app->security->generatePasswordHash($this->new_password);
            $this->role = "guest";
        }
        return parent::beforeSave($insert);
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach (Yii::$app->authManager->getAssignments($this->id) as $assignment)
            Yii::$app->authManager->revoke(
                Yii::$app->authManager->getRole($assignment->roleName),
                $this->id);
        Yii::$app->authManager->assign(Yii::$app->authManager->getRole($this->role), $this->id);
    }

    public function afterDelete()
    {
        foreach (Yii::$app->authManager->getAssignments($this->id) as $assignment)
            Yii::$app->authManager->revoke(
                Yii::$app->authManager->getRole($assignment->roleName),
                $this->id);
    }
}
