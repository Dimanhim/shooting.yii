<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Role;

/* @var $this yii\web\View */
/* @var $model common\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <div class="row">
        <div class="col">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <?php if(!$model->isNewRecord) : ?>
            <?= $this->render('_rights', [
                    'model' => $model,
            ]) ?>
        <?php endif; ?>
    </div>


</div>
