<?php

namespace app\controllers;

use app\models\User;
use app\models\Favourite;
use yii\web\Controller;
use yii\filters\Cors;

class UserController extends Controller
{
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
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

    public function actionGetUserData($userId)
    {
        $model = User::find()->where(['id' => $userId])->one();
        if (empty($model)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'User not found.',
            ];
        }

        $favourites = Favourite::find()->where(['user_id' => $userId])->all();
        $model->favourites = $favourites;

        if (file_exists($model->profile_picture)) {
            $type = pathinfo($model->profile_picture, PATHINFO_EXTENSION);
            $data = file_get_contents($model->profile_picture);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            $model->profile_picture = $base64;
        } else {
            $model->profile_picture = null;
        }

        return [
            'user' => $model,
            'favourites' => $favourites,
        ];
    }

    public function actionUploadUserPhoto()
    {
        $userId = \Yii::$app->request->post('user_id');
        $uploadDir = '../css/images/' . $userId;

        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $user = User::find()->where(['id' => $userId])->one();
                if (!empty($user)) {
                    $user->profile_picture = $uploadFile;
                    if (!$user->save()) {
                        if ($user->hasErrors()) {
                            foreach ($user->errors as $error) {
                                return [
                                    'status' => 'error',
                                    'message' => $error[0],
                                ];
                            }
                        }
                        return [
                            'status' => 'error',
                            'message' => 'Could not save user photo.',
                        ];
                    }
                }

                \Yii::$app->response->statusCode = 200;
                return [
                    'status' => 'success',
                    'message' => 'Fișierul a fost încărcat cu succes!',
                ];
            } else {
                \Yii::$app->response->statusCode = 400;
                return [
                    'status' => 'error',
                    'message' => 'Eroare la mutarea fișierului.',
                ];
            }
        } else {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Niciun fișier încărcat sau eroare la încărcare.',
            ];
        }
    }

    public function actionUpdatePassword()
    {
        $post = \Yii::$app->request->post();

        $userModel = User::find()->where(['id' => $post['user_id']])->one();
        if (empty($userModel)) {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'User not found.',
            ];
        }

        if (\Yii::$app->security->validatePassword($post['old_password'], $userModel->password_hash)) {
            $userModel->password_hash = \Yii::$app->security->generatePasswordHash($post['new_password']);
            if (!$userModel->save()) {
                if ($userModel->hasErrors()) {
                    foreach ($userModel->errors as $error) {
                        return [
                            'status' => 'error',
                            'message' => $error[0],
                        ];
                    }
                }
                return [
                    'status' => 'error',
                    'message' => 'Could not update password.',
                ];
            }

            \Yii::$app->response->statusCode = 200;
            return [
                'status' => 'success',
                'message' => 'Password updated successfully.',
            ];
        } else {
            \Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'Invalid old password.',
            ];
        }
    }
}