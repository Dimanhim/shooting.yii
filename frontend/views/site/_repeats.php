<?= $form->field($model, 'repeat_id')->checkbox() ?>
<div class="form-group repeat-group">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Повторения
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <table class="table table-borderless table-repeats">
                            <tr>
                                <td>
                                    Начало
                                </td>
                                <td>
                                    <?= $form->field($model, 'repeat_day_begin', ['template' => '{input}'])->textInput(['class' => 'form-control select-date-form']) ?>
                                </td>
                                <td>
                                    <i class="bi bi-calendar"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Повтор
                                </td>
                                <td>
                                    <?=
                                    $form->field($model, 'repeat_type', ['template' => '{input}'])
                                        ->dropDownList(
                                            $model->getRepeatDataNames(),
                                            [
                                                'prompt' => '[Не выбрано]',
                                                'class' => 'form-control select-period-o',
                                                'options' => $model->getOptionsForDropdown(),
                                            ]
                                        )
                                    ?>
                                </td>
                                <td>
                                    <i class="bi bi-back"></i>
                                </td>
                            </tr>
                            <?php foreach($model->repeatData as $repeatDataId => $repeatData) : ?>
                                <tr class="hidden-field" data-type-id="<?= $repeatDataId ?>">
                                    <td>
                                        <?= $repeatData['type_name'] ?>
                                    </td>
                                    <td>
                                        <div class="repeats-days repeats-days-o">
                                            <?php foreach($repeatData['values'] as $valueId => $value) : ?>
                                                <div class="repeat-values">
                                                    <label for="<?= $repeatDataId.'-'.$valueId ?>">
                                                        <?= $value ?>
                                                        <?= $form->field($model, 'repeat_type_values[]', ['template' => '{input}'])->textInput(['type' => 'checkbox', 'data-type' => $repeatData['btn_type'], 'id' => $repeatDataId.'-'.$valueId, 'value' => $valueId]) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?= $repeatData['icon'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .repeat-values input {
        display: none;
    }
    .repeat-values label {
        display: inline-block;
        list-style: none;
        padding: 5px;
        border: 1px solid #ccc;
        margin-left: 2px;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 5px;
    }
    .repeat-values label.active {
        background: rgba(0,0,0,0.1);
    }
    .table-repeats td {
        vertical-align: middle;
    }
    .repeats-days {
        padding-left: 0;
        margin-bottom: 0;
        display: flex;
    }
    .repeats-days li {
        display: inline-block;
        list-style: none;
        padding: 5px;
        border: 1px solid #ccc;
        margin-left: 2px;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 5px;
    }
    .repeats-days li.active {
        background: rgba(0,0,0,0.1);
    }
    .hidden-field {
        display: none;
    }
    .table-repeats .form-group {
        margin-bottom: 0;
    }
</style>
