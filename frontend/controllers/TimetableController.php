<?php

namespace frontend\controllers;

use common\components\Helper;
use common\models\BaseModel;
use common\models\Log;
use common\models\Place;
use common\models\TimetableForm;
use common\models\User;
use Yii;
use common\models\Timetable;
use frontend\models\TimetableSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
// http://shooting.yii/timetable/change-value?timetable_id=43&attributeName=phone&value=55555
// http://shooting.yii/timetable/update-logs?timetable_id=43
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
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => false,
                            'actions' => ['update-logs'],
                            'roles' => ['reception', 'instructor'],
                        ],
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
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
        if(Yii::$app->request->isAjax) {
            $model = new Timetable();
            if($model->load(Yii::$app->request->get())) {
                $model->date = strtotime($model->date);
                $model->phone = Helper::phoneFormat($model->phone);
                if($model->save()) {
                    $responce['result'] = true;
                    $responce['message'] = 'Запись успешно добавлена';
                }
            }
        }
        return json_encode($responce);
    }

    public function actionEditRecord()
    {
        $responce = [
            'result' => false,
            'message' => 'Произошла ошибка редактирования записи',
            'id' => null,
        ];
        if(Yii::$app->request->isAjax) {
            $model = Timetable::findOne(Yii::$app->request->get('Timetable')['id']);
            if($model->load(Yii::$app->request->get())) {
                $model->date = strtotime($model->date);
                $model->phone = Helper::phoneFormat($model->phone);

                if($model->save()) {
                    $responce['result'] = true;
                    $responce['message'] = 'Запись успешно изменена';
                    $responce['id'] = $model->id;
                }
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $responce;
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
                    $timeDiff = $timetable->time_to - $timetable->time_from;
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
    public function actionResizeRecord($timetable_id, $start_height, $stop_height)
    {
        $responce = ['result' => false];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->request->isAjax) {
            if($timetable_id && $start_height && $stop_height) {
                if($timetable = Timetable::findOne($timetable_id)) {

                    $diff = $stop_height - $start_height;
                    if($diff > 0) {
                        $diff = ceil($diff / Timetable::BASE_ROW_hEIGHT);

                    }

                    $timetable->time_to = $timetable->time_from + $diff * Timetable::DIFF_COUNT_SECONDS;

                    if($timetable->save()) {
                        $responce['result'] = true;
                    }
                }
            }
        }
        return $responce;
    }

    public function actionChangeValue($timetable_id, $attributeName, $value)
    {
        $responce = [
            'result' => false,
            'html' => '',
            'html_2' => '',
            'message' => 'Произошла ошибка изменения значения',
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($timetable = Timetable::findOne($timetable_id)) {
            if($attributeName == 'phone') {
                $value = Helper::phoneFormat($value);
            }
            if($attributeName == 'date') {
                $value = strtotime($value);
            }

            $timetable->$attributeName = $value;
            if($timetable->time_from >= $timetable->time_to) {
                $responce['message'] = 'Время окончания сеанса превышает время его начала '.Helper::getTimeAsString($timetable->$attributeName) ;
                return $responce;
            }
            if($timetable->save(false)) {
                $responce['result'] = true;
                if($attributeName == 'service_id') {
                    $responce['html'] = $timetable->service ? $timetable->service->name : '';
                }
                elseif($attributeName == 'place_id') {
                    $responce['html'] = $timetable->place ? $timetable->place->name : '';
                }
                elseif($attributeName == 'date') {
                    $responce['html'] = date('d.m.Y', $timetable->date);
                }
                else {
                    $responce['html'] = $timetable->$attributeName;
                }
                $responce['message'] = 'Поле "' . $timetable->attributeLabels()[$attributeName] . '" изменено успешно';
            }
        }
        return $responce;
    }

    /**
     * @param $place_id
     * @param $date
     * @return array
     */
    /*public function actionSetPlaceDate($place_id, $date)
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
    }*/

    public function actionUpdateLogs($timetable_id)
    {
        if(Yii::$app->request->isAjax) {
            $responce = [
                'result' => false,
                'html' => '',
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($logs = Log::find()->where(['timetable_id' => $timetable_id])->orderBy(['created_at' => SORT_DESC])->all()) {
                $responce['html'] = $this->renderPartial('//site/_logs', [
                    'logs' => Log::groupLogs($logs),
                ]);
                $responce['result'] = true;
            }
            return $responce;
        }
    }

    public function actionUpdatePlaceAccordeon($place_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $responce = [
            'result' => false,
            'html' => '',
            'adress_id' => null,
        ];
        if(Yii::$app->request->isAjax) {
            if(($place = Place::findOne($place_id)) && ($adress = $place->adress)) {
                $responce['html'] = $this->renderPartial('//site/adress/_places', [
                    'adress' => $adress,
                ]);
                $responce['result'] = true;
                $responce['adress_id'] = $adress->id;
            }
        }
        return $responce;
    }
}
