<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Place */

$this->title = 'Добавление стрельбища';
$this->params['breadcrumbs'][] = ['label' => 'Стрельбище', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="place-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
