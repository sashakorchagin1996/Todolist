<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
        
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
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

    public function due_date_to_datetime($due_date)
{
    $checkdate1 = DateTime::createFromFormat("d.m.y H:i",$due_date);
    $checkdate2 = DateTime::createFromFormat("d.m.y",$due_date);
    if ($checkdate1 != false ) {
         $datetime = $checkdate1;
    }
    else { 
        $datetime = $checkdate2;
        $datetime->setTime(0,0,0);
    }
    return $datetime->format('Y-m-d H:i:s');
}

public function due_date_correct_time_exist($due_date)
{
    return boolval(DateTime::createFromFormat("d.m.y H:i",$due_date));
}

public function due_date_correct_time_not_exist($due_date)
{
    return boolval(DateTime::createFromFormat("d.m.y",$due_date));
}

public function due_date_correct($due_date)
{
    $checkdate1 = due_date_correct_time_exist($due_date);
    $checkdate2 = due_date_correct_time_not_exist($due_date);
    return  $checkdate1 || $checkdate2;

}

public function datetime_to_due_date($datetime,$time_exist = 1)
{
    if (is_null($datetime)) {
        return "";
    }
    $due_date = new Datetime($datetime);
    if ($time_exist == 1)
        return $due_date->format('d.m.y H:i');
    else 
        return $due_date->format('d.m.y');
}

}
