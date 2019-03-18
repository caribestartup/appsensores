<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\helpers\UiHelper;
use app\models\SignupForm;
use app\models\Maquina;
use app\models\UserPin;
use app\models\User;
use app\models\Totales;
use app\models\TotalesSearch;
use app\models\Parciales;
use app\models\ParcialesSearch;

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
                'only' => ['logout', 'index','signup','proccess'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['proccess','signup'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                        $valid_roles = ['Production Manager'];
                        return User::roleInArray($valid_roles) && User::isActive();
                        }
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }
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

     public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {

            if ($user = $model->signup()) {
                $pin = new UserPin();
                $pin->user = $user->getId();
                $pin->pin = 123456;
                $pin->save();
                UiHelper::alert('<i class="icon fa fa-user"></i> Nuevo usuario registrado', UiHelper::SUCCESS);
                return $this->redirect(['/user/index']);

            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionProccess()
    {

        $searchModelTotales = new TotalesSearch();
        $dataProviderTotales = $searchModelTotales->search(Yii::$app->request->queryParams);
         $searchModelParciales = new ParcialesSearch();
        $dataProviderParciales = $searchModelParciales->search(Yii::$app->request->queryParams);


        return $this->render('proccess', [
            'searchModelTotales' => $searchModelTotales,
            'dataProviderTotales' => $dataProviderTotales,
            'searchModelParciales' => $searchModelParciales,
            'dataProviderParciales' => $dataProviderParciales,
        ]);

    }

}
