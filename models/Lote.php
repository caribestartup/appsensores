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
            'id' => Yii::t('app', 'Lot ID'),
            'identificador' => Yii::t('app', 'Identifier'),
            'pedido' => Yii::t('app', ''),
            'cantidad' => Yii::t('app', 'Amount'),
            'velocidad' => Yii::t('app', 'Speed'),
            'maquina_id' => Yii::t('app', 'Machine'),
            'estado' => Yii::t('app', 'Status'),
        ];
    }


    public function LotAvailable()
    {
        $lotes = (new \yii\db\Query())
        ->select('lote.*, pedido.identificador as order')
        ->leftJoin('pedido', 'pedido.id = lote.pedido')
        ->from('lote')
        ->where([
            'lote.maquina_id' => 0
        ])
        ->orderBy('pedido.identificador')
        ->all();

        return $lotes;
    }


}
