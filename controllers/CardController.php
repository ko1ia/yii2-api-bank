<?php
/**
 * Created by PhpStorm.
 * User: Николай
 * Date: 27.09.2019
 * Time: 18:13
 */

namespace app\controllers;


use app\models\Card;
use app\models\Credit;
use app\models\Historycard;
use app\models\Score;
use yii\rest\ActiveController;
use app\models\Help;

class CardController extends ActiveController
{
    public $modelClass = 'app/models/Card';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => BankAuthorization::className(),
            'only' => ['card-list', 'score-list', 'credit-list', 'pay', 'history-card', 'block'],
        ];

        return $behaviors;
    }

    public function actionCardList()
    {
        $cards = Card::find()->where(['user_id' => \Yii::$app->user->id])->all();

        return Help::response(200, 'Cards list', true, ['cards' => $cards]);
    }

    public function actionScoreList()
    {
        $scores = Score::find()->where(['id_user' => \Yii::$app->user->id])->all();

        return Help::response(200, 'Score list', true, ['score' => $scores]);
    }

    public function actionCreditList()
    {
        $credit = Credit::find()->where(['id_user' => \Yii::$app->user->id])->all();

        return Help::response(200, 'Credit list', true, ['credit' => $credit]);
    }

    public function actionPay()
    {
        $card_source = \Yii::$app->request->post('card_number_source');
        $card_dest = \Yii::$app->request->post('number_check');
        $sum = \Yii::$app->request->post('sum');

        if(!Card::find()->where(['number' => $card_source])->exists()){
            return Help::response(400, 'Card not found', false, ['message' => 'Card not found']);
        }
        if(Card::find()->where(['number' => $card_source])->one()->block == 'true' ||
            Card::find()->where(['number' => $card_dest])->one()->block == 'true') {
            return Help::response(400, 'Card block', false, ['message' => 'Card block']);
        }

        if(Card::find()->where(['number' => $card_source])->one()->user_id == \Yii::$app->user->id) {
            if(Card::find()->where(['number' => $card_dest])->exists()){
                $card = Card::find()->where(['number' => $card_source])->one();
                $card->sum -= $sum;
                if($card->save(false)){
                    $history = new Historycard();
                    $history->id_card = $card->id;
                    $history->type = 'Вы перевели';
                    $history->sum = $sum;
                    $history->save(false);
                }
                $card_2 = Card::find()->where(['number' => $card_dest])->one();
                $card_2->sum += $sum;
                if($card_2->save(false)){
                    $history = new Historycard();
                    $history->id_card = $card_2->id;
                    $history->type = 'Вам перевели';
                    $history->sum = $sum;
                    $history->save(false);
                }
                return Help::response(200, 'Pay success', true, ['message' => 'Pay success']);
            }
            return Help::response(400, 'Card not found', false, ['message' => 'Card not found']);
        }
        return Help::response(400, 'Card not your', false, ['message' => 'Card not your']);
    }

    public function actionHistoryCard()
    {
        $card_number = \Yii::$app->request->post('card_number');

        if(!Card::find()->where(['number' => $card_number])->exists()){
            return Help::response(400, 'Card not found', false, ['message' => 'Card not found']);
        }

        if(Card::find()->where(['number' => $card_number])->one()->user_id == \Yii::$app->user->id) {
            $card_id = Card::find()->where(['number' => $card_number])->one()->id;
            $history = Historycard::find()->where(['id_card' => $card_id])->all();

            return Help::response(200, 'History card', true, ['history' => $history]);
        }
        return Help::response(400, 'Card not your', false, ['message' => 'Card not your']);
    }

    public function actionBlock()
    {
        $card_number = \Yii::$app->request->post('card_number');
        $card = Card::find()->where(['number' => $card_number])->one();

        if($card->user_id == \Yii::$app->user->id) {
            if($card->block == 'true') {
                $card->block = 'false';
                $card->save(false);
                return Help::response(200, 'Card not blocked', true, ['message' => 'Card not blocked']);
            } else {
                $card->block = 'true';
                $card->save(false);
                return Help::response(200, 'Card blocked', true, ['message' => 'Card blocked']);
            }
        }
        return Help::response(400, 'Card not your', false, ['message' => 'Card not your']);
    }
}