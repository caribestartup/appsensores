<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class Pass extends Model
{
    public $old_pass;
    public $password;
    public $password_repeat;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
     
            [['old_pass', 'password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 6],
            ['old_pass', 'string', 'min' => 6],
            ['password_repeat','compare', 'compareAttribute' => 'password', 'message' => Yii::t("app","Las contraseñas deben coincidir"),],
        
        ];
    }
    
    public function attributeLabels(){

        return[
            'old_pass' => Yii::t('app','Old Password'),
            'password' => Yii::t('app','New Password'),
            'password_repeat' => Yii::t('app','Rewrite Password'),
         
        ];
    }
    
    
   
}
