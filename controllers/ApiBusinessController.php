<?php

namespace app\controllers;

use app\models\Business;
use app\models\Favourite;
use yii\web\Controller;

class ApiBusinessController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $favourites = Favourite::find()->asArray()->all();
        return [
            'favourites' => $favourites,
            'business' => Business::find()
                ->with('images') // Include relația 'images'
                ->asArray()
                ->all(),
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

        $msg = 'removed';
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

            $business->favourite = 1;
            $business->save();

            $msg = 'added';
        } else {
            $checkFavourite->delete();
            $business->favourite = 0;
            $business->save();
        }

        return [
            'status' => 'success',
            'message' => 'Favourite ' . $msg . ' successfully.',
        ];
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