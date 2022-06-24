<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Street */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="street-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить адрес?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'short_description:ntext',
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
