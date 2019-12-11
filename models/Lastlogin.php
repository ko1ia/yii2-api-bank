<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lastlogin".
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property string $time
 *
 * @property User $user
 */
class Lastlogin extends \yii\db\ActiveRecord
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
        return 'lastlogin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'time'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'time'], 'string', 'max' => 50],
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
            'date' => 'Date',
            'time' => 'Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
