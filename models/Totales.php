<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "totales".
 *
 * @property int $id
 * @property int $identificador
 * @property string $mac
 * @property int $programa
 * @property string $fecha
 * @property string $modelo
 * @property string $serie
 * @property int $camara
 * @property string $hora_inicio
 * @property string $hora_fin
 * @property int $total
 * @property int $total_error
 * @property int $cliente
 * @property int $operario
 * @property int $turno
 * @property int $total_tubos
 * @property int $ampollas_tubos
 * @property int $ampollas_previstas
 */
class Totales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'totales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identificador', 'mac', 'programa', 'fecha', 'modelo', 'serie', 'camara', 'hora_inicio', 'hora_fin', 'total', 'total_error', 'cliente', 'operario', 'turno', 'total_tubos', 'ampollas_tubos', 'ampollas_previstas'], 'required'],
            [['identificador', 'programa', 'camara', 'total', 'total_error', 'cliente', 'operario', 'turno', 'total_tubos', 'ampollas_tubos', 'ampollas_previstas'], 'integer'],
            [['fecha', 'hora_inicio', 'hora_fin'], 'safe'],
            [['mac'], 'string', 'max' => 50],
            [['modelo', 'serie'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identificador' => 'Identifier',
            'mac' => 'Machine',
            'programa' => 'Planned',
            'fecha' => 'Date',
            'modelo' => 'Model',
            'serie' => 'Serial',
            'camara' => 'Chamber',
            'hora_inicio' => 'Begin',
            'hora_fin' => 'End',
            'total' => 'Production',
            'total_error' => 'Total Errors',
            'cliente' => 'Client',
            'operario' => 'Operator',
            'turno' => 'Work Shift',
            'total_tubos' => 'Total Tubos',
            'ampollas_tubos' => 'Ampollas Tubos',
            'ampollas_previstas' => 'Ampollas Previstas',
            'produccion' => 'Production',
        ];
    }

    public function getMachine()
    {
        $maquina = Maquina::find()->where(['maquina_id' => $this->mac])->one();
        return $maquina->nombre;
    }

    public function getUser()
    {
        $maquina = User::find()->where(['id' => $this->operario])->one();
        return $maquina->name." ".$maquina->surname;
    }

    public function getTurnon()
    {
        $maquina = Turno::find()->where(['id' => $this->turno])->one();
        return $maquina->identificador;
    }


}
