<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 27.09.2019
 * Time: 16:25
 */

namespace app\models;


class Help
{
    public function response($code, $status, $statusOnArray, $array = [])
    {
        \Yii::$app->response->setStatusCode($code, $status);

        if(is_bool($statusOnArray)){
            return array_merge([
                'status' => $statusOnArray,
            ], $array);
        }

        return $statusOnArray;
    }
}