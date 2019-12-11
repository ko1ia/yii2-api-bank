<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "score".
 *
 * @property int $id
 * @property int $id_user
 * @property string $number
 * @property string $sum
 *
 * @property Historyscore[] $historyscores
 * @property User $user
 */
class Score extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'number', 'sum'], 'required'],
            [['id_user'], 'integer'],
            [['number'], 'string', 'max' => 20],
            [['sum'], 'string', 'max' => 50],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'number' => 'Number',
            'sum' => 'Sum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryscores()
    {
        return $this->hasMany(Historyscore::className(), ['id_score' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
