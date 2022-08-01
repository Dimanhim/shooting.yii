<?php

namespace frontend\controllers;

use common\models\BaseModel;
use common\models\TimetableForm;
use Yii;
use common\models\Timetable;
use frontend\models\TimetableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TimetableController implements the CRUD actions for Timetable model.
 */
class TimetableController extends Controller
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
     * Lists all Timetable models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TimetableSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Timetable model.
     * @param int $id ID
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
     * Creates a new Timetable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Timetable();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Timetable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Timetable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Timetable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Timetable::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
    AJAX
    */

    public function actionCreateRecord()
    {
        $responce = [
            'result' => false,
            'message' => 'Произошла ошибка добавления записи',
        ];
        //if(Yii::$app->request->isAjax) {
            $form = new TimetableForm();
            $model = new Timetable();
            if($form->load(Yii::$app->request->get())) {
                $form->date = strtotime($form->date);
                $model->addAttributes($form);
                if($model->save()) {
                    $responce['result'] = true;
                    $responce['message'] = 'Запись успешно добавлена';
                }
            }
        //}
        return json_encode($responce);
    }

    /**
     * @param $start_timetable_id
     * @param $start_time
     * @param $stop_time
     * @param $stop_date
     * @param $stop_place
     * @return array
     */
    public function actionDropRecord($start_timetable_id, $start_time, $stop_time, $stop_date, $stop_place)
    {
        $responce = ['result' => false];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->request->isAjax) {
            if($start_timetable_id && $start_time && $stop_time && $stop_date && $stop_place) {
                $stop_date = strtotime($stop_date);
                if($timetable = Timetable::findOne($start_timetable_id)) {
                    $timeDiff = $timetable->time_from - $timetable->time_to;
                    $timetable->time_from = $stop_time;
                    $timetable->time_to = $stop_time + $timeDiff;
                    $timetable->date = $stop_date;
                    $timetable->place_id = $stop_place;
                    if($timetable->save()) {
                        $responce['result'] = true;
                    }
                }
            }
        }
        return $responce;
    }

    /**
     * @param $place_id
     * @param $date
     * @return array
     */
    public function actionSetPlaceDate($place_id, $date)
    {
        $responce = [
            'result' => true,
            'config' => []
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new BaseModel();
        $cacheName = $model->getCacheName('temp_places');
        $tempPlaces = $model->getConfig()[$cacheName];

        if($tempPlaces && array_key_exists($date, $tempPlaces)) {
            $arrayForDate = $tempPlaces[$date];
            $tempPlaces[$date] = array_merge($arrayForDate, [$place_id]);
            $tempPlaces[$date] = array_unique($tempPlaces[$date]);
        }
        else {
            $tempPlaces[$date][] = $place_id;
            $tempPlaces[$date] = array_unique($tempPlaces[$date]);
        }

        $model->setCacheTempPlaces($tempPlaces);

        $responce['config'] = $model->getConfig();
        return $responce;
    }
}
