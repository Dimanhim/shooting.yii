<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TimetableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Timetables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timetable-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Timetable', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'unique_id',
            'name',
            'description:ntext',
            'short_description:ntext',
            //'client_id',
            //'date',
            //'time_from:datetime',
            //'time_to:datetime',
            //'place_id',
            //'user_id',
            //'is_active',
            //'deleted',
            //'position',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Timetable $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
