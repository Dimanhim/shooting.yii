<?php

namespace backend\controllers;

use Yii;
use common\models\Role;
use common\models\User;
use backend\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Role models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Role();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate() && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }









    /**
     * https://itreviewchannel.ru/yii2-kontrol-dostupa-na-osnove-rolej-role-based-access-control/
     */
    public function actionCreateRole()
    {
        echo "<pre>";
        print_r(Yii::$app->authManager->getRoles());
        echo "</pre>";
        exit;
        /*
         * Добавляем роли
         *
        $roleAdministrator = Yii::$app->authManager->createRole('administrator');
        $roleAdministrator->description = 'Администратор';
        Yii::$app->authManager->add($roleAdministrator);

        $roleModerator = Yii::$app->authManager->createRole('moderator');
        $roleModerator->description = 'Модератор';
        Yii::$app->authManager->add($roleModerator);

        $roleUser = Yii::$app->authManager->createRole('user');
        $roleUser->description = 'Пользователь';
        Yii::$app->authManager->add($roleUser);
        */
        /*
         * Привязываем роль к пользователю
        $ivanov = User::findOne(1);
        $roleAdministrator = Yii::$app->authManager->getRole('administrator');
        Yii::$app->authManager->assign($roleAdministrator, $ivanov->id);
        */
        /*
         * Удаляем роль
        $roleModerator = Yii::$app->authManager->createRole('user');
        Yii::$app->authManager->remove($roleModerator);
        */
        return 'success';
    }
}
