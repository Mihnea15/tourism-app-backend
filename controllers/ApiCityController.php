<?php

namespace app\controllers;

use app\models\City;
use yii\web\Controller;

class ApiCityController extends Controller
{
    public function actionIndex()
    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        return City::find()->asArray()->all();

        return ['id' => 1, 'name' => 'Jakarta'];
    }
}