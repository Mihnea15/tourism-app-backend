<?php

namespace app\controllers;

use app\models\User;
use Codeception\PHPUnit\Constraint\Page;
use yii\filters\Cors;
use yii\web\Controller;

class UserController extends Controller
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

    public function actionRegister()
    {
        $post = \Yii::$app->request->post();
        if (empty($post)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'No data provided.',
            ];
        }

        $checkEmail = User::find()->where(['email' => $post['email']])->one();
        if (!empty($checkEmail)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Email already exists.',
            ];
        }

        $user = new User();
        $user->first_name = trim($post['first_name']);
        $user->last_name = trim($post['last_name']);
        $user->email = trim($post['email']);
        $user->password_hash = \Yii::$app->security->generatePasswordHash($post['password']);
        if (!$user->save()) {
            if ($user->hasErrors()) {
                foreach ($user->errors as $error) {
                    \Yii::$app->response->statusCode = 400;
                    return [
                        'status' => 'error',
                        'message' => $error[0],
                    ];
                }
            }
            return [
                'status' => 'error',
                'message' => 'Failed to save user.',
            ];
        }

        \Yii::$app->response->statusCode = 200;
        return [
            'status' => 'success',
            'message' => 'User registered successfully.',
            'user_id' => $user->id,
            'username' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
        ];
    }

    public function actionLogin()
    {
        $post = \Yii::$app->request->post();
        if (empty($post)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'No data provided.',
            ];
        }

        $user = User::find()->where(['email' => $post['email']])->one();

        if ($user && \Yii::$app->security->validatePassword($post['password'], $user->password_hash)) {
            \Yii::$app->response->statusCode = 200;
            return [
                'status' => 'success',
                'message' => 'User logged in successfully.',
                'user_id' => $user->id,
                'username' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
            ];
        } else {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Invalid email or password.',
            ];
        }
    }
}