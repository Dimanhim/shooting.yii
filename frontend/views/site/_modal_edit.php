<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\components\Helper;
use common\models\Place;
use common\models\Service;

?>
<div class="timetable-item modal fade" id="timetable-edit" tabindex="-1" aria-labelledby="timetable-edit-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-change-date" style="background: #5F95E9; color: #fff">
                <div class="modal-header-email">
                    Редактирование записи
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'form-edit-timetable', 'method' => 'get', 'action' => 'timetable/edit-record', 'options' => ['class' => 'form send-data', 'data-id' => $model->id]]) ?>
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($model, 'name', ['template' => '{input}{error}'])->textInput(['placeholder' => "Имя"]) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($model, 'date', ['template' => '{input}{error}'])->textInput(['placeholder' => "Выберите дату", 'class' => 'form-control select-date-form']) ?>
                    </div>
                    <div class="col-12">
                        <?= $form->field($model, 'place_id')->dropDownList(Place::getList(), ['prompt' => '[Не выбрано]']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-1 col-phone-text">
                        с
                    </div>
                    <div class="col-5">
                        <?= $form->field($model, 'time_from', ['template' => '{input}{error}'])->dropDownList(Helper::getTimesSecondsArray(), ['class' => 'form-control timetableform-time_from-o', 'prompt' => '[Не выбрано]']) ?>
                    </div>
                    <div class="col-1 col-phone-text">
                        до
                    </div>
                    <div class="col-5">
                        <?= $form->field($model, 'time_to', ['template' => '{input}'])->dropDownList([], ['class' => 'form-control timetableform-time_to']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'qty', ['template' => '{input}'])->textInput(['placeholder' => "Количество человек", 'class' => 'form-control']) ?>
                <?= $form->field($model, 'service_id', ['template' => '{input}'])->dropDownList(Service::getList(), ['prompt' => '[Услуга не выбрана]']) ?>
                <?= $form->field($model, 'phone', ['template' => '{input}'])->textInput(['placeholder' => "Номер телефона", 'class' => 'form-control phone-mask']) ?>
                <?= $form->field($model, 'description', ['template' => '{input}'])->textarea(['placeholder' => 'Текст описания']) ?>
                <?= $form->field($model, 'id', ['template' => '{input}'])->hiddenInput() ?>
                <?= Html::submitButton('Сохранить', ['class' => "btn btn-success"]) ?>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

