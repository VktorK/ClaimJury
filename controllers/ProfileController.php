<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use app\models\Profile;
use app\models\User;

/**
 * ProfileController handles user profile management
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays user profile
     *
     * @return string
     */
    public function actionView()
    {
        $model = $this->findModel();
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates user profile
     *
     * @return string|Response
     */
    public function actionUpdate()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            // Обработка загрузки аватара
            $avatarFile = UploadedFile::getInstance($model, 'avatar');
            if ($avatarFile) {
                if ($model->uploadAvatar($avatarFile)) {
                    Yii::$app->session->setFlash('success', 'Аватар успешно обновлен.');
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при загрузке аватара.');
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Профиль успешно обновлен.');
                return $this->redirect(['view']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при сохранении профиля.');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes user avatar
     *
     * @return Response
     */
    public function actionDeleteAvatar()
    {
        $model = $this->findModel();
        
        if ($model->avatar) {
            $uploadPath = Yii::getAlias('@webroot/uploads/avatars/');
            $filePath = $uploadPath . $model->avatar;
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $model->avatar = null;
            $model->save(false);
            
            Yii::$app->session->setFlash('success', 'Аватар удален.');
        }

        return $this->redirect(['view']);
    }

    /**
     * Finds the Profile model based on current user
     *
     * @return Profile
     * @throws NotFoundHttpException
     */
    protected function findModel()
    {
        $userId = Yii::$app->user->id;
        $model = Profile::findOne(['user_id' => $userId]);
        
        if ($model === null) {
            // Создаем профиль если его нет
            $user = User::findOne($userId);
            if ($user) {
                $model = $user->createProfile();
            } else {
                throw new NotFoundHttpException('Пользователь не найден.');
            }
        }

        return $model;
    }
}
