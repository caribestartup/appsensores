<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "local".
 *
 * @property integer $local_id
 * @property string $nombre
 * @property string $plano
 */
class Local extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'local';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'plano'], 'required'],
            [['nombre'], 'string', 'max' => 50],
            [['plano'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'local_id' => Yii::t('app', 'Local ID'),
            'nombre' => Yii::t('app', 'Name'),
            'plano' => Yii::t('app', 'Plane'),
        ];
    }
}
