<?php

namespace app\models;

use Yii;
use app\models\Pedido;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "lote".
 *
 * @property integer $id
 * @property string $identificador
 * @property string $inicio
 * @property string $fin
 */
class Insidencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'insidencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['maquina_id'], 'required'],
            [['descripcion'], 'string', 'max' => 255],
            [['inicio'], 'required'],
            [['value'], 'integer'],
            [['lote_id'], 'required'],
            [['lote_id'], 'integer'],
            [['value'], 'required'],
            [['value'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'Lot ID'),
            'maquina_id' => Yii::t('app', 'Machine ID'),
            'usuario_id' => Yii::t('app', 'User'),
            'inicio' => Yii::t('app', 'Start'),
            'fin' => Yii::t('app', 'End'),
            'descripcion' => Yii::t('app', 'Description'),
            'value' => Yii::t('app', 'Value'),
            'lote_id' => Yii::t('app', 'Lot'),
        ];
    }


}
