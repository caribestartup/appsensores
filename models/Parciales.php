<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parciales".
 *
 * @property int $id
 * @property int $id_totales
 * @property string $ventana
 * @property int $total
 * @property int $total_error
 * @property string $nombre_ventana
 */
class Parciales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parciales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_totales', 'ventana', 'total', 'total_error', 'nombre_ventana'], 'required'],
            [['id_totales', 'total', 'total_error'], 'integer'],
            [['ventana', 'nombre_ventana'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_totales' => 'Id Totales',
            'ventana' => 'Window',
            'total' => 'Production',
            'total_error' => 'Total Errors',
            'nombre_ventana' => 'Window Name',
            'turno' => 'Work Shift',
            'operario' => 'Operators',
            'maquina' => 'Machine',
            'fecha' => 'Date',
        ];
    }

    public function getTotales()
    {
        $totales = Totales::find()->where(['id' => $this->id_totales])->one();
        return $totales;
    }

    public function getTurno()
    {
        return $this->getTotales()->getTurnon();
    }

    public function getOperario()
    {
        return $this->getTotales()->getUser();
    }

    public function getMaquina()
    {
        return $this->getTotales()->getMachine();
    }

    public function getWindow()
    {
        return Error::findOne($this->ventana)->error;
    }

    public function getFecha()
    {
        return $this->getTotales()->hora_inicio;
    }
}
