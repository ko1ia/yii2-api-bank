<?php

namespace app\controllers;


use yii\filters\auth\HttpBearerAuth;

class BankAuthorization extends HttpBearerAuth
{
    public function handleFailure($response)
    {
        \Yii::$app->response->setStatusCode('401', 'Unauthorized');
        \Yii::$app->response->data = [
            'message' => 'You are not authorized'
        ];
    }
}