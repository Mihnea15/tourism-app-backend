<?php

namespace app\controllers;

use app\models\Business;
use app\models\Favourite;
use yii\web\Controller;

class ApiBusinessController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Adaugă comportamentul CORS
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                'Origin' => ['*'], // Permite toate originile pentru dezvoltare
                'Access-Control-Allow-Credentials' => false, // Setează la false dacă folosești Origin '*'
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['Origin', 'Content-Type', 'Accept', 'Authorization', 'X-Requested-With'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $favourites = Favourite::find()->asArray()->all();
        return [
            'favourites' => $favourites,
            'business' => Business::find()->asArray()->all(),
        ];
    }

    public function actionAddFavourite()
    {
        $post = \Yii::$app->request->post();
        if (empty($post)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'No data provided.',
            ];
        }

        $business = Business::findOne($post['business_id']);
        if (empty($business)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Business not found.',
            ];
        }

        $checkFavourite = Favourite::find()->where(['business_id' => $post['business_id'], 'user_id' => $post['user_id']])->one();
        if (empty($checkFavourite)) {
            $favourite = new Favourite();
            $favourite->business_id = $post['business_id'];
            $favourite->user_id = $post['user_id'];
            if (!$favourite->save()) {
                if ($favourite->hasErrors()) {
                    foreach ($favourite->errors as $error) {
                        \Yii::$app->response->statusCode = 400;
                        return [
                            'status' => 'error',
                            'message' => $error[0],
                        ];
                    }
                }
            }
        } else {
            $checkFavourite->delete();
        }
    }

    public function actionGetFavourites($userId)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $favouriteBusinesses = [];
        $favourites = Favourite::find()->where(['user_id' => $userId])->asArray()->all();
        foreach ($favourites as $favourite) {
            $favouriteBusinesses[] = Business::find()->where(['id' => $favourite['business_id']])->asArray()->one();
        }

        return $favouriteBusinesses;
    }
}