<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Trainer */

$this->title = 'Добавление инструктора';
$this->params['breadcrumbs'][] = ['label' => 'Инструкторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
