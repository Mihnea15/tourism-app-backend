<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use yii\web\Controller;

class ApiCityController extends Controller
{
    public function beforeAction($action)
    {
        if (\Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Origin', '*');
            \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
            \Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
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