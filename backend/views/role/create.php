<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Role */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Права', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
