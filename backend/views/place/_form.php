<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Street;
use common\models\Color;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Place */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="place-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'street_id')->dropDownList(Street::getList(), ['prompt' => '[Не выбрано]']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'color_id')->dropDownList(Color::getList(), ['prompt' => '[Не выбрано]']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
