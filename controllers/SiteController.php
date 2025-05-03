<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Personaldata;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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


    public function actionSignup()
    {
        $PersonalD = new Personaldata(); // Ajusta el modelo de Datos Personales según tu aplicación.
        $User = new User(); // Ajusta el modelo de Usuario según tu aplicación.

        if ($PersonalD->load(Yii::$app->request->post()) && $PersonalD->save()) {
            // Los datos personales se guardaron con éxito, ahora puedes crear un usuario.
            // Puedes utilizar los datos personales para llenar el modelo de Usuario si es necesario.
            $now = \Yii::$app->formatter;
            $User->username = $PersonalD->Ci;
            $User->setPassword($PersonalD->Ci);
            $User->Created_at = $now->asDatetime(new \DateTime(), 'php:Y-m-d H:i:s');
            $User->Auth_key = \Yii::$app->security->generateRandomString();
            
            // Aquí puedes configurar otros campos del modelo Usuario según tus necesidades.

            if ($User->save()) {
                // El usuario se creó con éxito. Datos personales también
                \Yii::$app->session->setFlash('success', 'Usuario creado con éxito.');
                return $this->redirect(['site/login']); // Reemplaza 'site/login' con la ruta de tu página de inicio de sesión

                // Redirige al usuario a la página de inicio de sesión (ajusta la URL según tu configuración).
                // return $this->redirect(['site/login']); // Cambia 'site/login' a la URL real de tu página de inicio de sesión.
            } else {
                \Yii::$app->session->setFlash('error', 'Error al crear el usuario.');
            }
        } elseif (Yii::$app->request->isPost) {
            // Si se envió el formulario pero no se cargaron ni guardaron datos, muestra un mensaje de error.
            \Yii::$app->session->setFlash('error', 'Error al guardar los datos personales.');
        }

        // Renderiza la vista de registro.
        return $this->render('signup', [
            'model' => $PersonalD, 
            'niveles' => $PersonalD->niveles,   // Pasa el modelo de usuario si deseas mostrarlo en la vista.
        ]);
    }
}
