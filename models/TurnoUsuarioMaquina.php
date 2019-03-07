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
class TurnoUsuarioMaquina extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'turno_usuario_maquina';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['maquina_id'], 'required'],
            [['turno_usuario_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'id' => Yii::t('app', 'TurnoUsuarioMaquina ID'),
            'maquina_id' => Yii::t('app', 'Machine'),
            'turno_usuario_id' => Yii::t('app', 'Turn User'),
            'fecha' => Yii::t('app', 'Date')
        ];
    }


}
