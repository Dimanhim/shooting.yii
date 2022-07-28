<?php

use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Color;

?>
<div class="timetable-item modal fade" id="timetable-item" tabindex="-1" aria-labelledby="timetable-item-label" aria-hidden="true">
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
                                'style' => $model->getItemStyle(),
                            ]
                    )
                ?>

                <?//= $form->field($model, 'invite_persons', ['template' => "{input}"])->textInput(['placeholder' => "E-mail", 'type' => 'email', 'class' => '']) ?>
                <?php ActiveForm::end() ?>
                <form action="" class="modal-form">
                    <input class="form-control input-style input-persons" type="text" name="persons" placeholder="Введите пользователей" >
                    <input class="form-control input-style input-clock" type="text" name="persons" placeholder="Введите пользователей" >
                    <input class="form-control input-style input-invite" type="text" name="persons" placeholder="Пригласите пользователей" >
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Сохранить</button>
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
