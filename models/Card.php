<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $number
 * @property string $sum
 * @property string $block
 *
 * @property User $user
 * @property Historycard[] $historycards
 */
class Card extends \yii\db\ActiveRecord
{
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['id']);
        unset($fields['user_id']);

        return $fields;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'number', 'sum', 'block'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'sum'], 'string', 'max' => 50],
            [['number'], 'string', 'max' => 16],
            [['block'], 'string', 'max' => 10],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'number' => 'Number',
            'sum' => 'Sum',
            'block' => 'Block',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorycards()
    {
        return $this->hasMany(Historycard::className(), ['id_card' => 'id']);
    }
}
