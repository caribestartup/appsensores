<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_pin".
 *
 * @property int $id
 * @property int $user
 * @property int $pin
 */
class UserPin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_pin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user', 'pin'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'pin' => 'Pin',
        ];
    }
}
