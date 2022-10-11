<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\components\Helper;
use common\models\Place;
use common\models\Service;

?>
<div class="timetable-item modal fade" id="timetable-create" tabindex="-1" aria-labelledby="timetable-create-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-change-date" style="background: #5F95E9; color: #fff">
                <div class="modal-header-email">
                    Новая запись
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!--
                <div class="modal-header-title">
                    <div class="modal-view-header-item">
                        Новая запись
                    </div>
                </div>
                -->
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'form-create-timetable', 'method' => 'get', 'action' => 'timetable/create-record', 'options' => ['class' => 'form send-data']]) ?>
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($model, 'name', ['template' => '{input}{error}'])->textInput(['placeholder' => "Имя"]) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($model, 'date', ['template' => '{input}{error}'])->textInput(['placeholder' => "Выберите дату", 'class' => 'form-control select-date-form', 'value' => $date]) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($model, 'place_id')->dropDownList(Place::getList(), ['prompt' => '[Не выбрано]', 'value' => $place]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-1 col-phone-text">
                        с
                    </div>
                    <div class="col-5">
                        <?= $form->field($model, 'time_from', ['template' => '{input}{error}'])->dropDownList(Helper::getTimesSecondsArray(), ['class' => 'form-control timetableform-time_from-o', 'prompt' => '[Не выбрано]', 'value' => $time]) ?>
                    </div>
                    <div class="col-1 col-phone-text">
                        до
                    </div>
                    <div class="col-5">
                        <?= $form->field($model, 'time_to', ['template' => '{input}'])->dropDownList([], ['class' => 'form-control timetableform-time_to']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'qty', ['template' => '{input}'])->textInput(['placeholder' => "Количество человек", 'class' => 'form-control']) ?>
                <?//= $form->field($model, 'service_id', ['template' => '{input}'])->dropDownList(Service::getList(), ['prompt' => '[Услуга не выбрана]']) ?>
                <?= $form->field($model, 'service_name', ['template' => '{input}'])->textInput(['placeholder' => 'Введите услугу']) ?>
                <?= $form->field($model, 'phone', ['template' => '{input}'])->textInput(['placeholder' => "Номер телефона", 'class' => 'form-control phone-mask']) ?>
                <?= $this->render('_repeats', [
                    'form' => $form,
                    'model' => $model,
                ]) ?>
                <?= $form->field($model, 'description', ['template' => '{input}'])->textarea(['placeholder' => 'Текст описания']) ?>
                <div class="form-group">
                    <p class="info-message"></p>
                </div>

                <?= Html::submitButton('Добавить', ['class' => "btn btn-success"]) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
