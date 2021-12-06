<?php

namespace app\models;

use app\controllers\UserController;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class EditProfileForm extends Model
{
    /**
     * @var User
     */
    private $_user;

    public $id;
    public $username;
    public $first_name;
    public $last_name;
    public $patronymic;
    public $city;
    public $birthday;
    public $email;
    public $password;
    public $rememberMe = true;
    public $photo;
    public $status;

    public $photoFile;


    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->id =$user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->patronymic = $user->patronymic;
        $this->birthday = $user->birthday;
        $this->photo = $user->photo;
        $this->city = $user->city;
        $this->status = $user->status;


        parent::__construct($config);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {

        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['status','boolean'],
            ['email', 'string', 'max' => 255],

            [['photoFile','photo'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],

//            ['password', 'required'],
            ['birthday','date','format' => 'yyyy-MM-dd'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],

            [['first_name','last_name','patronymic','city','birthday'],'string'],

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findIdentity($this->username);
        }

        return $this->_user;
    }

    public function edit()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = $this->_user;

        if (!empty($this->username)) {
            $user->username = $this->username;
        }

        if (!empty($this->first_name)) {
            $user->first_name = $this->first_name;
        }
        if (!empty($this->last_name)) {
            $user->last_name = $this->last_name;
        }
        if (!empty($this->patronymic)) {
            $user->patronymic = $this->patronymic;
        }
        if (!empty($this->city)) {
            $user->city = $this->city;
        }
        if (!empty($this->birthday)) {
            $user->birthday = $this->birthday;
        }
        if (isset($this->status)) {
            $user->status = $this->status;
        }

        $this->photoFile = UploadedFile::getInstance($this, 'photo');
        if (!empty($this->photoFile)) {
//            $user->photo = $file;
            $this->upload();
            $user->photo = $this->photo;
        }

        if (!empty($this->password)) {
            $user->setPassword($this->password);
        }


        return $user->save() ;
    }

    public function upload()
    {
        if ($this->validate()) {
            $filename = $this->photoFile->baseName . '.' . $this->photoFile->extension;
            $folder = User::getFullUploadPathByType(USER::UPLOADS_TYPE_PHOTO);

            if (!is_file($folder)) {
                \yii\helpers\FileHelper::createDirectory($folder, $mode = 0775, $recursive = true);
            }

            $path = $folder. $filename;
            $this->photoFile->saveAs($path);
            $this->photo = $filename;
            return true;
        } else {
            return false;
        }
    }




}
