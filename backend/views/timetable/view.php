<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Timetable */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Timetables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="timetable-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'unique_id',
            'name',
            'description:ntext',
            'short_description:ntext',
            'client_id',
            'date',
            'time_from:datetime',
            'time_to:datetime',
            'place_id',
            'user_id',
            'is_active',
            'deleted',
            'position',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
