<?php

namespace app\controllers;

use app\models\Business;
use app\models\City;
use app\models\Trails;
use yii\rest\Controller;
use yii\web\Response;

class ApiCityController extends Controller
{
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'cities' => City::find()->asArray()->orderBy(['id' => SORT_ASC])->all(),
            'business' => Business::find()->asArray()->all(),
            'trails' => Trails::find()->asArray()->all(),
        ];
    }
}
