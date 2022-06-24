<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Place */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Стрельбища', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="place-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить стрельбище?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'street_id',
            'name',
            'description:ntext',
            'short_description:ntext',
            'price',
            [
                'attribute' => 'color_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->color) {
                        return $data->color->getViewBlock($data->color->background);
                    }
                }
            ],
            [
                'attribute' => 'is_active',
                'value' => function($data) {
                    return $data->is_active ? 'Да' : 'Нет';
                }
            ],
            [
                'attribute' => 'deleted',
                'value' => function($data) {
                    return $data->deleted ? 'Да' : 'Нет';
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
        ],
    ]) ?>

</div>
