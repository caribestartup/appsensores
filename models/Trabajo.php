<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajo".
 *
 * @property int $id
 * @property int $operador
 * @property string $inicio
 * @property string $fin
 * @property int $maquina
 * @property int $turno
 */
class Trabajo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['operador', 'maquina', 'turno'], 'required'],
            [['operador', 'maquina', 'turno'], 'integer'],
            [['inicio', 'fin'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operador' => 'Operador',
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'maquina' => 'Maquina',
            'turno' => 'Turno',
        ];
    }
}
