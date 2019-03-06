<?php

namespace app\models;

use Yii;
use app\models\Turno;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_turno".
 *
 * @property int $id
 * @property int $user
 * @property int $turno
 */
class UserTurno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_turno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user'], 'safe'],
            [['turno'], 'required'],
            [['turno'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'turno' => 'Work Shift',
        ];
    }

     public function getdropdownturnos(){
        $dropdown = Turno::find()->asArray()->all();
        return ArrayHelper::map($dropdown, 'id', 'identificador');
    }   

    public function getTurnoname(){
        $find_turno = Turno::find() ->where(['id' => $this->turno])->one();
        return $find_turno->identificador;
    }
}
