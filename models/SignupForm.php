<?php
namespace app\models;

use app\models\User;
use yii\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use app\models\Role;


/**
 * Signup form  
 */ 
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $file;
    public $name;
    public $surname;
    public $role;


    /**
     * @inheritdoc
     */
    public function attributeLabels(){

        return[
            'username' => Yii::t('app','User'),
            'email' => Yii::t('app','email'),
            'password' => Yii::t('app','Password'),
            'password_repeat' => Yii::t('app','Rewrite Password'),
            'file' => Yii::t('app', 'Avatar'), 
            'name' => Yii::t('app', 'Name'),
            'surname' => Yii::t('app', 'Surname'), 
            'role' => Yii::t('app', 'Permission'), 
        ];
    }



    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This user has been taken.'],
            ['username', 'string', 'min' => 6, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email has been taken.'],

            /*['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat','compare', 'compareAttribute' => 'password', 'message' => Yii::t("app","Passwords must be the same"),],
            ['password_repeat','required'],*/

            [['file'],'file'],
            
            ['role','required'],


            [['name','surname'], 'required'],
            [['name','surname'], 'string', 'max' => 50],
            [['name','surname'], 'match', 'pattern' => '/^[a-zA-Záéíóúàèìòùâêîôûäëïöü” “]+$/'],


        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();

            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword("123456");
            $user->generateAuthKey();
            $user->name = ucwords(strtolower($this->name));
            $user->surname = ucwords(strtolower($this->surname));
            $user->role = $this->role;
 

            $imageName = $user->username; 
            $user->file = UploadedFile::getInstance($this, 'file');
            if($user->file != null){
            $user->file->saveAs('avatarpics/'.$imageName.'.'.$user->file->extension);
            $user->avatar = 'avatarpics/'.$imageName.'.'.$user->file->extension;
            }
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
    
     public function getdropdownroles(){
        $dropdown = Role::find()->asArray()->all();
        return ArrayHelper::map($dropdown, 'id', 'role');
    }
	

   
}

