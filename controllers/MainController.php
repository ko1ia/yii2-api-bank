<?php

namespace app\controllers;

use app\models\Bankomat;
use Yii;
use yii\rest\ActiveController;
use app\models\Help;
use yii\rest\Controller;

class MainController extends ActiveController
{
    public $modelClass = 'app/models/Bankomat';
    public function actionValute()
    {
        function get_currencies() {
            $xml = simplexml_load_file('https://cbr.ru/scripts/XML_daily.asp');
            $currencies = array();
            foreach ($xml->xpath('//Valute') as $valute) {
                $currencies[(string)$valute->CharCode] = (float)str_replace(',', '.', $valute->Value);
            }
            return $currencies;
        }
        $currencies = get_currencies();

        $result = [
            'USD' => [
                'day' => date('d.m.y H:i:s'),
                'value' => $currencies['USD'],
            ],
            'EUR' => [
                'day' => date('d.m.y H:i:s'),
                'value' => $currencies['EUR'],
            ],
            'UAH' => [
                'day' => date('d.m.y H:i:s'),
                'value' => $currencies['USD'],
            ],
        ];
        return Help::response('200', 'Today Valutes', true, $result);
    }

    public function actionBankomat()
    {
        $bankomats = Bankomat::find()->all();

        return Help::response('200', 'Bankomats', true, $bankomats);
    }
}
