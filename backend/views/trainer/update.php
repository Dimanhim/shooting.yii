<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Trainer */

$this->title = 'Редактирование инструктора: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Инструкторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="trainer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
