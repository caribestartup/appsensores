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

    public function exits($tur, $maq, $fec)
    {
      $maquina = (new \yii\db\Query())
      ->from('turno_usuario_maquina')
      ->where([
        'turno_usuario_id' => $tur,
        'borrar' => 0,
        'maquina_id' => $maq,
        'fecha' => $fec
      ])
      ->all();

      if (sizeof($maquina) > 0) {
        return true;
      } else {
        return false;
      }
    }

    public function getMaquinaAssign()
    {

        $maquina = (new \yii\db\Query())
        ->select('maquina.maquina_id, maquina.nombre, turno_usuario_maquina.fecha, turno_usuario_maquina.id')
        ->leftJoin('user_turno', 'user_turno.id = turno_usuario_maquina.turno_usuario_id')
        ->leftJoin('user', 'user.id = user_turno.user')
        ->leftJoin('maquina', 'maquina.maquina_id = turno_usuario_maquina.maquina_id')
        ->from('turno_usuario_maquina')
        ->where([
        'user.id' => Yii::$app->user->identity->getId(),
        'turno_usuario_maquina.borrar' => 0,
        'turno_usuario_maquina.fecha' => date("Y-m-d")
        ])
        ->all();

        return $maquina;
    }


}
