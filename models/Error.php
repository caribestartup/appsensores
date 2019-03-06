<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "error".
 *
 * @property int $id
 * @property string $error
 */
class Error extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'error';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['error'], 'required'],
            [['error'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'error' => 'Error',
        ];
    }
}
