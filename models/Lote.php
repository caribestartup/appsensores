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
class Lote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identificador'], 'required'],
            [['identificador'], 'string', 'max' => 255],
            [['pedido'], 'required'],
            [['pedido'], 'integer'],
            [['velocidad'], 'required'],
            [['cantidad'], 'required'],
            [['estado'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'Lote ID'),
            'identificador' => Yii::t('app', 'Identifier'),
            'pedido' => Yii::t('app', 'Pedido'),
            'cantidad' => Yii::t('app', 'Cantidad'),
            'velocidad' => Yii::t('app', 'Velocidad'),
            'estado' => Yii::t('app', 'Estado'),
        ];
    }


}
