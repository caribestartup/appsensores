<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "lote".
 *
 * @property integer $id
 * @property string $identificador
 * @property string $inicio
 * @property string $fin
 */
class Genericos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'genericos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pregunta'], 'required'],
            [['pregunta', 'respuesta'], 'string', 'max' => 255],
            [['lote_id', 'maquina_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'Lot ID'),
            'pregunta' => Yii::t('app', 'Question'),
            'respuesta' => Yii::t('app', 'Answer'),
            'lote_id' => Yii::t('app', 'Lot'),
            'maquina_id' => Yii::t('app', 'Machine'),
            'user_id' => Yii::t('app', 'User'),
            'fecha' => Yii::t('app', 'Date'),
        ];
    }


}
