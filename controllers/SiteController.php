<?php

namespace app\controllers;

use app\models\Message;
use app\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['logout','message'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'login' => ['post'],
                    'index' => ['get'],
                    'about' => ['get'],
                    'users' => ['get'],
                    'messages' => ['get'],
                    'message' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays chat page.
     *
     * @return string
     */
    public function actionIndex()
    {
        $message = new Message();
        $messages = Message::getLastMessages();

        $user = new LoginForm();
        $users = User::getActiveUsers();

        return $this->render('chat', compact('message', 'messages', 'users', 'user'));
    }

    /**
     * Return active users
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function actionUsers()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return User::getActiveUsers();
    }

    /**
     * Save new message
     *
     * @return string|Response
     */
    public function actionMessage()
    {
        if (!\Yii::$app->request->isPjax) {
            return $this->goHome();
        }
        $message = new Message();
        if ($message->load(\Yii::$app->request->post()) && $message->validate()) {
            $message->save();
            $message = new Message();
        }

        return $this->renderAjax('_message_form', compact('message'));
    }

    /**
     * Return array of last message (json).
     *
     * @return string|array
     */
    public function actionMessages()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id', 0);
        return $id ? Message::getLastMessagesById($id) : Message::getLastMessages();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!\Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->load(\Yii::$app->request->post());

        return $model->login() ?
            $this->renderAjax('_logout_form') :
            $this->renderAjax('_login_form', ['user' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response|string
     */
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        if (\Yii::$app->request->isPjax) {
            return $this->renderAjax('_login_form', ['user' => new LoginForm()]);
        }

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
