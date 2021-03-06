<?php

namespace frontend\controllers;

use common\components\Helper;
use common\models\BaseModel;
use common\models\TimetableForm;
use common\models\Timetable;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
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
                //'only' => ['logout', 'signup', 'index', 'login'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //'actions' => ['logout', 'index', 'get-times-to', 'show-create-modal', 'update-table'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
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
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new BaseModel();
        $user = User::getUser();
        return $this->render('index', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }





    /**
    AJAX
     */
    public function actionShowCreateModal($time = null, $place = null)
    {
        $data = Yii::$app->request->get('param');
        $model = new TimetableForm();
        $responce = [
            'result' => true,
            'html' => $this->renderPartial('_modal_create', [
                'data' => $data,
                'model' => $model,
                'time' => $time,
                'place' => $place,
            ]),
        ];
        return json_encode($responce);
    }
    /**
    AJAX
     */
    public function actionShowViewModal($id)
    {
        $model = new Timetable();
        if(!$model = Timetable::findOne($id)) {
            throw new NotFoundHttpException('???????????????? ???? ??????????????');
        }
        return $this->renderPartial('_modal_view', [
           'model' => $model,
        ]);
    }

    public function actionGetTimesTo($time)
    {
        $responce = [
            'result' => true,
            'html' => null,
        ];
        $result = [];
        if($times = Helper::getTimesSecondsArray()) {
            foreach($times as $key => $val) {
                if($key > $time) {
                    $result[$key] = $val;
                }
            }
            $responce['result'] = true;
            $responce['html'] = Helper::getTimesOptions($result);
        }
        return json_encode($responce);
    }

    public function actionUpdateTable()
    {
        if(Yii::$app->request->isAjax) {
            $model = new BaseModel();
            return $model->getColumns();
        }
    }

    public function actionChangeDate()
    {
        if(Yii::$app->request->isAjax) {
            $date = Yii::$app->request->get('date');
            $model = new BaseModel();
            $model->setCacheDate($date);
            $responce = [
                'result' => true,
                'date' => $model->getCacheDate(),
            ];
            return json_encode($responce);
        }
    }

    public function actionSetNewDate($action = null)
    {
        $model = new BaseModel();

        if($action) {
            $date = $model->getCacheDate()['date_timestamp'];

            if($action == 'plus') {
                $newDate = $date + 86400;
                $model->setCacheDate(date('d.m.Y', $newDate));
            }
            elseif($action == 'minus') {
                $newDate = $date - 86400;
                $model->setCacheDate(date('d.m.Y', $newDate));
            }
        }
        else {
            $model->setCacheDate(date('d.m.Y'));
        }
        return $this->redirect('/');




        /*$date = Yii::$app->request->get('date');
        if()
        $model = new BaseModel();
        $model->setCacheDate($date);
        $responce = [
            'result' => true,
            'date' => $model->getCacheDate(),
        ];
        */
    }

    public function actionChangePlaces()
    {
        if(Yii::$app->request->isAjax) {
            if($data = Yii::$app->request->get('data')) {
                $ids = [];
                foreach($data as $value) {
                    $ids[] = $value['id'];
                }

;            }
            $model = new BaseModel();
            $model->setCachePlaces($ids);
            $responce = [
                'result' => true,
                'date' => $model->getCachePlaces(),
            ];
            return json_encode($responce);
        }
    }

    public function actionUpdateMain()
    {
        $timeBegin = time() - 10;
        $timeEnd = time();
        return Timetable::find()->select(['created_at'])->where(['between', 'created_at', $timeBegin, $timeEnd])->exists();
    }
}
