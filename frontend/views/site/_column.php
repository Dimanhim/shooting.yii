<?php

use common\components\Helper;

$titleStyles = $model->getRandomTitleStyle();

$columnValues = $model->getColumnValues();

?>
<div class="col-sm column-o">
    <div class="calendar-column">
        <div class="calendar-column-header" style="border-bottom: 1px solid <?= $model->getBackgroundColor() ?>;">
            <div class="calendar-column-header-text" style="<?= $model->getColorStyles() ?>">
                <?= $model->name ?>
                <a href="#" class="delete-column-o" data-id="<?= $model->id ?>">
                    <i class="bi bi-file-earmark-excel"></i>
                </a>
            </div>
        </div>
        <div class="calendar-column-body">
            <?php foreach(Helper::getTimesArray() as $time) : ?>
            <div class="column-line column-line-o" data-time="<?= Helper::formatTimeFromHours($time) ?>" data-place="<?= $model->id ?>">
                <div class="row">
                    <div class="col-sm-1 column-calendar-block">
                        <div class="column-calendar-time">
                            <?= $time ?>
                        </div>
                    </div>
                    <?= $model->getColumnValue($time, $columnValues) ?>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
