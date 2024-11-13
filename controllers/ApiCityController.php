<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use yii\web\Controller;

class ApiCityController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'cities' => City::find()->asArray()->all(),
            'business' => Business::find()->asArray()->all(),
        ];
    }
}
