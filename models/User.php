<?php

namespace app\models;


use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;


/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username Логин
 * @property string|null $first_name Имя
 * @property string|null $last_name Фамилия
 * @property string|null $patronymic Отчество
 * @property string|null $birthday День рождения
 * @property string|null $photo Изображение
 * @property string|null $city Город
 * @property string $auth_key Ключ аутентификации cookies
 * @property string $password Пароль
 * @property string|null $password_reset_token Токен сброса пароля
 * @property string|null $verification_token Токен подтверждения почты
 * @property string $email Email
 * @property int $status Статус
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_IMPORT = 'import';

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE_LABEL = 'Неактивен';
    const STATUS_ACTIVE_LABEL = 'Активный';


    public static $uploadsPath = "uploads";

    const UPLOADS_TYPE_PHOTO = 0;
    const UPLOADS_TYPE_EXPORT = 1;
    const UPLOADS_TYPE_IMPORT = 2;
    const UPLOADS_PHOTO_NAME = 'photo';
    const UPLOADS_IMPORT_NAME = 'import';
    const UPLOADS_EXPORT_NAME = 'export';

    const UPLOADS_DIRS = [
        self::UPLOADS_TYPE_PHOTO => self::UPLOADS_PHOTO_NAME,
        self::UPLOADS_TYPE_EXPORT => self::UPLOADS_EXPORT_NAME,
        self::UPLOADS_TYPE_IMPORT => self::UPLOADS_IMPORT_NAME
    ];



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
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['username', 'photo', 'password', 'password_reset_token','verification_token', 'email'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 120],
            [['patronymic'], 'string', 'max' => 60],
            [['city'], 'string', 'max' => 83],
            [['auth_key'], 'string', 'max' => 32],
            [['birthday'],'date','format' => 'yyyy-MM-dd'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['status'],'in','range' => array_keys($this->getStatusList())],
            [['username', 'email'], 'required','on' => self::SCENARIO_IMPORT],
            [['username', 'email'], 'unique','on' => self::SCENARIO_IMPORT],
            [['password'], 'string','on' => self::SCENARIO_IMPORT],
            [['password'], 'default','value' => '11','on' => self::SCENARIO_IMPORT],
            [['auth_key'],'string','max' => 120],

            [['password_reset_token','verification_token','auth_key'],'default','value' => '', 'on' => self::SCENARIO_IMPORT],

            [['password_reset_token','auth_key'], 'unique'],
            [['password_reset_token','auth_key'],'default'],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'patronymic' => 'Отчество',
            'birthday' => 'День рождения',
            'photo' => 'Изображение',
            'city' => 'Город',
            'auth_key' => 'Ключ аутентификации cookies',
            'password' => 'Пароль',
            'password_reset_token' => 'Токен сброса пароля',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => new Expression('NOW()'),
            ]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_IMPORT] = [
            'username','email','patronymic','first_name','last_name',
            'password_reset_token','verification_token','auth_key','birthday',
            'city','status','created_at','updated_at','photo'
        ];
        return $scenarios;
    }

//    public function beforeSave($insert)
//    {
//        if ($this->scenario === 'insert') {
//            $this->created_at = new Expression('NOW()');
//        } elseif ($this->scenario === 'update') {
//            $this->update_at = new Expression('NOW()');
//        }
//    }


    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INACTIVE  => self::STATUS_INACTIVE_LABEL,
            self::STATUS_ACTIVE  => self::STATUS_ACTIVE_LABEL,
        ];
    }

    public function getStatus()
    {
        $data = $this::getStatusList();

        return isset($data[$this->status]) ?: $data[$this->status];
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
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
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

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }


    protected function findFormModel($id) {
        if (($model = EditProfileForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function getUploadsFolderByType($type = self::UPLOADS_TYPE_PHOTO)
    {
        return self::UPLOADS_DIRS[$type];
    }

    public static function getUploads() {
        $uploadPath = Yii::getAlias('@webroot');
        $path = $uploadPath;
        if (!empty(self::$uploadsPath)) $path .= DIRECTORY_SEPARATOR . self::$uploadsPath;
        return $path;
    }

    public static function getFullUploadPathByType($type = self::UPLOADS_TYPE_PHOTO) {
        $uploads = self::getUploads();
        $directory_name = !(empty(self::getUploadsFolderByType($type))) ? $uploads.DIRECTORY_SEPARATOR.self::getUploadsFolderByType($type).DIRECTORY_SEPARATOR : $uploads;

        return $directory_name;
    }

    public static function getPhotoLinkByUserId($user_id)
    {
        $user = self::findOne($user_id);
        $image = $user->photo;
        $default = false;
//        $default_profile = Yii::$app->getHomeUrl().DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'default-profile.jpg';
        $default_profile = Url::to('@web/images/default-profile.jpg');
        if (empty($image)) {
            $image = $default_profile;
            $default = true;
        }
        if ($default) {
            return $image;
        }
        $url = Url::to('@web/uploads/'.self::getUploadsFolderByType(USER::UPLOADS_TYPE_PHOTO).DIRECTORY_SEPARATOR.$image);
        return $url;
    }

    public static function isPhotoExistByUserId($user_id) {
        $user = self::findOne($user_id);
        $photo = $user->photo;
        return is_file(Yii::$app->basePath . DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.self::$uploadsPath.DIRECTORY_SEPARATOR.self::getUploadsFolderByType(USER::UPLOADS_TYPE_PHOTO).'/'.$photo);
    }

    public static function removeProfilePhoto($id) {
        $user = User::findOne($id);
        $photo = $user->photo;
        unlink(Yii::$app->basePath . DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.self::$uploadsPath.DIRECTORY_SEPARATOR.self::getUploadsFolderByType(USER::UPLOADS_TYPE_PHOTO).'/'.$photo);
        if (!empty($user)) {
            $user->photo = null;
            $user->save();
        }
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
     * Finds user by username reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByUsername($username)
    {

        return static::findOne([
            'username' => $username,
//            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by username reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
        ]);
    }

}
