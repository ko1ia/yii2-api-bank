<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historyscore".
 *
 * @property int $id
 * @property int $id_score
 * @property string $type
 * @property string $sum
 *
 * @property Score $score
 */
class Historyscore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historyscore';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_score', 'type', 'sum'], 'required'],
            [['id_score'], 'integer'],
            [['type', 'sum'], 'string', 'max' => 50],
            [['id_score'], 'exist', 'skipOnError' => true, 'targetClass' => Score::className(), 'targetAttribute' => ['id_score' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_score' => 'Id Score',
            'type' => 'Type',
            'sum' => 'Sum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScore()
    {
        return $this->hasOne(Score::className(), ['id' => 'id_score']);
    }
}
