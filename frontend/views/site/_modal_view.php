<?php

use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Color;
use common\components\Helper;
use yii\helpers\Html;
use common\models\Service;
use common\models\Place;
use common\models\User;

$modalClass = (User::isAdmin() || User::isReception()) ? 'timetable-editable-view' : '';
?>
<div class="timetable-item modal fade <?= $modalClass ?>" id="timetable-item" tabindex="-1" aria-labelledby="timetable-item-label" aria-hidden="true" data-id="<?= $model->id ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
                $styles = '';
                if(($model->place && $model->place->color)) {
                    if($model->place->color->background) {
                        $styles .= 'background: '.$model->place->color->background.'; ';
                    }
                    if($model->place->color->text) {
                        $styles .= 'color: '.$model->place->color->text.'; ';
                    }
                }
            ?>
            <div class="modal-header btn-change-date" style="<?= $styles ?>">
                <div class="modal-header-email">
                    <a class="modal-title modal-view-header-item" id="timetable-item-label">
                        e.cherepov2@vistrel.ru
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-header-title">
                    <div class="modal-view-header-item">
                        <?= $model->place ? $model->place->name : '' ?>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'form-view']) ?>
                <?=
                $form->field($formModel, 'color_id')
                    ->dropDownList(
                        ArrayHelper::map(
                                Color::find()
                                    ->where(['group_id' => Color::GROUP_TIMETABLE_RECORD])
                                    ->asArray()
                                    ->all(),
                                'id', 'name'
                        ),
                        [
                            'prompt' => '[Не выбрано]',
                            'class' => 'timetable-change-color-o',
                            'data-id' => $model->id,
                            'style' => $model->getItemStyle(false),
                        ]
                    )
                ?>


                <?//= $form->field($model, 'invite_persons', ['template' => "{input}"])->textInput(['placeholder' => "E-mail", 'type' => 'email', 'class' => '']) ?>
                <?php ActiveForm::end() ?>
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-phone"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['name'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->name ?>
                            </span>
                            <input type="text" placeholder="Имя" class="form-control editable-field-input editable-field-input-o" value="<?= $model->name ?>" data-attribute="name">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-calendar-date"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['date'] ?>:
                        </td>
                        <td>
                            <input id="select-date-timetable-view" class="select-date-timetable-view" type="text" value="<?= date('d.m.Y', $model->date) ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-calendar-date"></i>
                        </td>
                        <td>
                            Время
                        </td>
                        <td>
                            с
                            <?= Html::dropDownList('Дата от', $model->time_from, Helper::getTimesSecondsArray(), ['class' => 'option-input option-input-from-o', 'prompt' => '[Не выбрано]']) ?>
                            до
                            <?= Html::dropDownList('Дата до', $model->time_to, Helper::getTimesSecondsArray(), ['class' => 'option-input option-input-to-o', 'prompt' => '[Не выбрано]']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-phone"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['phone'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->phone ?>
                            </span>
                            <input type="text" placeholder="Номер телефона" class="form-control phone-mask editable-field-input editable-field-input-o" value="<?= $model->phone ?>" data-attribute="phone">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-person"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['qty'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->qty ?>
                            </span>
                            <input type="text" placeholder="Количество человек" class="form-control editable-field-input editable-field-input-o" value="<?= $model->qty ?>" data-attribute="qty">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-person-badge"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['service_id'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->service_name ?>
                            </span>
                            <input type="text" placeholder="Услуга" class="form-control editable-field-input editable-field-input-o" value="<?= $model->service_name ?>" data-attribute="service_name">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-person-bounding-box"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['place_id'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->place ? $model->place->name : '' ?>
                            </span>
                            <?= Html::dropDownList($model->attributeLabels()['place_id'], $model->place_id, Place::getList(), ['prompt' => '[Не выбрано]', 'class' => 'form-control editable-field-input editable-field-input-o', 'data-attribute' => 'place_id']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20px" >
                            <i class="bi bi-text-center"></i>
                        </td>
                        <td>
                            <?= $model->attributeLabels()['description'] ?>:
                        </td>
                        <td>
                            <span class="editable-field-text editable-field-text-o">
                                <?= $model->description ?>
                            </span>
                            <textarea class="form-control editable-field-input editable-field-input-o" data-attribute="description"><?= $model->description ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <i class="bi bi-infinity"></i>
                        </td>
                        <td>
                            Повтор
                        </td>
                        <td>
                            <?= $model->getRepeatString() ?>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td colspan="2">



                            <?php $formRepeat = ActiveForm::begin(['id' => 'form-repeat', 'action' => 'timetable/create-repeat']) ?>
                                <?= $this->render('_repeats', [
                                    'form' => $formRepeat,
                                    'model' => $model,
                                ]) ?>
                            <?= $formRepeat->field($model, 'id', ['template' => '{input}'])->hiddenInput() ?>
                            <?= Html::submitButton('Сохранить повтор', ['class' => "btn btn-success"]) ?>
                            <?php ActiveForm::end() ?>
                        </td>
                    </tr>
                </table>
            </div>



            <div class="modal-footer">
                <p class="info-message"></p>
                <?php if(User::isAdmin()) : ?>
                    <div id="logs">
                        <?= $this->render('_logs', [
                            'logs' => $logs,
                        ]) ?>
                    </div>
                <?php endif; ?>
                <a href="#" class="btn btn-success btn-close-o" data-id="">Сохранить</a>
                <a href="#" class="btn btn-danger btn-delete-record-o pull-right" data-id="">Удалить</a>

                <?php if($model->repeat_id) : ?>
                    <a href="#" class="btn btn-danger btn-delete-infinity-o" data-id="">Удалить все повторы</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<style>
    <?php if($colors = Color::find()->all()) : ?>
        <?php foreach($colors as $color) : ?>
            #timetableviewform-color_id option[value="<?= $color->id ?>"] {
                background: <?= $color->background ?>;
                color: <?= $color->text ?>;
            }
        <?php endforeach; ?>
    <?php endif; ?>
</style>
