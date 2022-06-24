<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;

/* @var $this yii\web\View */
/* @var $model common\models\Color */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="color-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'background')->widget(ColorInput::className(), [
        'options' => ['placeholder' => 'Выберите цвет'],

    ]) ?>
    <?= $form->field($model, 'border')->widget(ColorInput::className(), [
        'options' => ['placeholder' => 'Выберите цвет'],

    ]) ?>
    <?= $form->field($model, 'text')->widget(ColorInput::className(), [
        'options' => ['placeholder' => 'Выберите цвет'],

    ]) ?>
    <?= $form->field($model, 'description')->textarea() ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
