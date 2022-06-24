<?php

use common\components\Helper;

$titleStyles = $model->getRandomTitleStyle();
?>
<div class="col-sm column-o">
    <div class="calendar-column">
        <div class="calendar-column-header" style="border-bottom: 1px solid <?= $model->getBackgroundColor() ?>;">
            <div class="calendar-column-header-text" style="<?= $model->getColorStyles() ?>">
                <?= $model->name ?>
                <a href="#" class="delete-column-o">
                    <i class="bi bi-file-earmark-excel"></i>
                </a>
            </div>
        </div>
        <div class="calendar-column-body">
            <?php foreach($model->getTimesArray() as $time) : ?>
            <div class="column-line" data-time="<?= $time ?>">
                <div class="row">
                    <div class="col-sm-1 column-calendar-block">
                        <div class="column-calendar-time">
                            <?= $time ?>
                        </div>
                    </div>
                    <?php for($i = 0; $i < mt_rand(1,5); $i++) : ?>
                    <div class="col-sm column-calendar-block">
                        <div class="calendar-column-body-item column-item-o" data-time="<?= Helper::formatTimeFromHours($time)  ?>" data-date="<?= strtotime(date('d.m.Y')) ?>" style="<?= $model->getRandomColumnStyle() ?>">
                            <?= $time.' - '.$model->getRandomText() ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
