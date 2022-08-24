<?php

use common\components\Helper;
use common\models\BaseModel;
use common\models\User;

$columnValues = $model->getColumnValues($result['id'], $result['date']);

$rows = BaseModel::COLUMN_ROWS;      // по две штуки в ряд
?>
<?php if(($countItem == 0) || ($countItem%$rows == 0)) : ?>
    <div class="row column-row-o">
<?php endif; ?>


<div class="col-sm column-o <?= $result['default'] ? '' : 'temp-column' ?>">
    <div class="calendar-column">
        <div class="calendar-column-header" style="border-bottom: 1px solid <?= $result['background_color'] ?> <?//= $model->getBackgroundColor() ?>;">
            <div class="calendar-column-header-text" style="<?= $result['styles'] ?><?//= $model->getColorStyles() ?>">
                <?= $result['name'] ?>
                 <!--
                <input class="change-date-column change-date-column-o" value="<?//= $result['date'] ?>" data-place="<?//= $result['id'] ?>" />
                -->
                <a href="#" class="delete-column-o" data-id="<?= $result['id'] ?>" data-date="<?= $result['date'] ?>">
                    <i class="bi bi-file-earmark-excel"></i>
                </a>
            </div>
        </div>
        <div class="calendar-column-body">
            <?php foreach(Helper::getTimesArray() as $time) : ?>
            <div class="column-line <?= preg_match('/30/', $time) ? 'time-half' : '' ?> <?php if(!User::isInstructor()) : ?>column-line-o<?php endif; ?>" data-time="<?= Helper::formatTimeFromHours($time) ?>" data-date="<?= $result['date'] ?>" data-place="<?= $result['id'] ?>">
                <div class="row">
                    <div class="col-sm-1 column-calendar-block">
                        <div class="column-calendar-time ">
                            <span><?= $time ?></span>
                        </div>
                    </div>
                    <?= $model->getColumnValue($time, $columnValues) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php if((($countItem + 1)%$rows) == 0) : ?>
    </div>
<?php endif; ?>
