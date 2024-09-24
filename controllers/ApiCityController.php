<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use yii\web\Controller;

class ApiCityController extends Controller
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
        return [
            'cities' => City::find()->asArray()->all(),
            'business' => Business::find()->asArray()->all(),
        ];
    }
}