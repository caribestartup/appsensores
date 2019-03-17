<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ordenador".
 *
 * @property int $ordenador_id
 * @property string $mac
 * @property int $maquina
 * @property int $estado
 */
class Ordenador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ordenador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'maquina'], 'required'],
            [['maquina'], 'integer'],
            [['uuid'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ordenador_id' => 'Ordenador ID',
            'uuid' => 'UUID',
            'maquina' => 'Machane',
        ];
    }
}
