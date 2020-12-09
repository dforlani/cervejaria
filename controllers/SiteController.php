<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\UploadForm;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use const YII_ENV_TEST;

class SiteController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
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
    public function actionIndex() {

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {

            return $this->goHome();
        }



        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }


        $model->senha = '';
        return $this->render('login', [
                    'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
//    public function actionLogout() {
//        Yii::$app->user->logout();
//
//        return $this->goHome();
//    }

    public function actionLogout() {
//        echo $token .'<br>';
//        echo Yii::$app->request->csrfToken.'<br>';
//        exit();
//        if ($token !== Yii::$app->request->csrfToken)
//            throw new HttpException(400, Yii::t('app', 'Invalid request. Please do not repeat this request again.'));

        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                    'model' => $model,
        ]);
    }

    public function actionChat() {

        return $this->render('chat', [
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionOi_mundo() {
        return $this->render('oi_mundo');
    }

    public function actionCalendario_full() {
        return $this->render('calendario_full');
    }

    public function actionCalendario_colunas_dia() {
        return $this->render('calendario_colunas_dia');
    }

    public function actionProposta() {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if (!$model->upload()) {
                print_r($model->errors);
            }
        }

        $arquivos = $model->listFiles();

        $arquivosProvider = new ArrayDataProvider([
            'allModels' => $arquivos,
            'sort' => [
                'attributes' => ['arquivo'],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);


        return $this->render('proposta', ['model' => $model, 'arquivosProvider' => $arquivosProvider]);
    }

}
