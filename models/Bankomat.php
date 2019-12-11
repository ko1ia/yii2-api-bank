<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bankomat".
 *
 * @property int $id
 * @property string $adress
 * @property string $type
 * @property string $time
 * @property string $status
 */
class Bankomat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bankomat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adress', 'type', 'time', 'status'], 'required'],
            [['adress'], 'string', 'max' => 100],
            [['type', 'time', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'adress' => 'Adress',
            'type' => 'Type',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }
}
