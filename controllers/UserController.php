<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 26.09.2019
 * Time: 21:18
 */

namespace app\controllers;


use app\models\Lastlogin;
use app\models\User;
use yii\rest\ActiveController;
use app\models\Help;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => BankAuthorization::className(),
            'only' => ['logout', 'info', 'password', 'logine', 'last'],
        ];

        return $behaviors;
    }


    public function actionLogin()
    {
        $user = new User();
        $user->load(\Yii::$app->request->post(), '');

        if($user = $user->login()){
            $lastlogin = new Lastlogin();
            $lastlogin->user_id = $user->id;
            $lastlogin->date = date('d.m.y');
            $lastlogin->time = date('H:i:s');
            $lastlogin->save();
            return Help::response(200, 'Authorization success', true, ['token' => $user->token]);
        }
        return Help::response(400, 'Authorization error', false, ['message' => 'Authorization error']);
    }

    public function actionLogout()
    {
        $username = \Yii::$app->request->post('username');

        $user = User::findOne(['username' => $username]);
        $user->token = '';
        if($user->save()) {
            return Help::response(200, 'Logout success', true, ['message' => 'Logout success']);
        }
        return Help::response(400, 'Logout error', false, ['message' => 'Logout error']);
    }

    public function actionLast()
    {
        $lastlogins = Lastlogin::find()->where(['user_id' => \Yii::$app->user->id])->all();

        return $lastlogins;


    }

    public function actionInfo()
    {
        $user = User::findOne(['id' => \Yii::$app->user->id]);

        return Help::response(200, 'Information User', true, ['info' => $user]);
    }

    public function actionPassword()
    {
        $user = User::findOne(['id' => \Yii::$app->user->id]);

        $user->password = \Yii::$app->request->post('password');
        if($user->save(false)) {
            return Help::response(200, 'Password change', true, ['message' => 'Password change']);
        }
    }

    public function actionLogine()
    {
        $user = User::findOne(['id' => \Yii::$app->user->id]);

        $user->username = \Yii::$app->request->post('username');
        if($user->save(false)) {
            return Help::response(200, 'Username change', true, ['message' => 'Username change']);
        }
    }
}