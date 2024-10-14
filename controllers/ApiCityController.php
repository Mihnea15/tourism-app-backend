<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use yii\web\Controller;

class ApiCityController extends Controller
{
    public function beforeAction($action)
    {
        $headers = \Yii::$app->getResponse()->getHeaders();

        // Setează header-urile CORS
        $headers->set('Access-Control-Allow-Origin', '*'); // Sau specifică 'http://localhost'
        $headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');

        if (\Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            \Yii::$app->end();
        }

        return parent::beforeAction($action);
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