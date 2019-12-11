<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credit".
 *
 * @property int $id
 * @property int $id_user
 * @property int $type
 * @property string $datatime
 * @property int $sum
 *
 * @property User $user
 */
class Credit extends \yii\db\ActiveRecord
{

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['id']);
        unset($fields['id_user']);

        return $fields;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'type', 'sum'], 'required'],
            [['id', 'id_user', 'type', 'sum'], 'integer'],
            [['datatime'], 'safe'],
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
            'type' => 'Type',
            'datatime' => 'Datatime',
            'sum' => 'Sum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
