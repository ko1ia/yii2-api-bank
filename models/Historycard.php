<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historycard".
 *
 * @property int $id
 * @property int $id_card
 * @property string $datetime
 * @property string $type
 * @property int $sum
 *
 * @property Card $card
 */
class Historycard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historycard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_card', 'type', 'sum'], 'required'],
            [['id', 'id_card', 'sum'], 'integer'],
            [['datetime'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['id_card'], 'exist', 'skipOnError' => true, 'targetClass' => Card::className(), 'targetAttribute' => ['id_card' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_card' => 'Id Card',
            'datetime' => 'Datetime',
            'type' => 'Type',
            'sum' => 'Sum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::className(), ['id' => 'id_card']);
    }
}
