<?php

/**

 * ПРЕДСТАВЛЯЕТ ВСЮ КОЛОНКУ СО СТРЕЛЬБИЩЕМ
 * ПЕРЕБИРАЕТ СТРОКИ СО ВРЕМЕНЕМ ИЗ МАССИВА
 * И ТУДА ПОДСТАВЛЯЕТ ЗАПИСИ ИЗ БАЗЫ

 */

use common\components\Helper;
use common\models\BaseModel;
use common\models\User;
use common\models\Place;
use common\models\Timetable;

/**

 * ПОЛУЧАЕТ МАССИВ С ДАННЫМИ О ЗАПИСЯХ
 * ОТДЕЛЬНОГО СТРЕЛЬБИЩА НА ОТДЕЛЬНУЮ ДАТУ

 */
$columnValues = $model->getColumnValues($result['id'], $result['date']);

$rows = BaseModel::COLUMN_ROWS;      // по две штуки в ряд

// массив с записями на конкретное стрельбище на конкретную дату
$testArray = Place::eachPlaceDateArray();

// количество временных промежутков
$timeGaps = count(Helper::getTimesArray());

$newTestArray = Place::donePlaceDateArray($columnValues, $date_timestamp, $place_id);
/*if($place_id == 2) {
    echo "<pre>";
    print_r($newTestArray);
    echo "</pre>";
    exit;
}*/

//$newTestArray = $columnValues;
/*echo "<pre>";
print_r($newTestArray);
echo "</pre>";
exit;*/





/**

 * ЗДЕСЬ ДЛЯ НАЧАЛА НУЖНО ОПРЕДЕЛИТЬ КОЛИЧЕСТВО КОЛОНОК СТРЕЛЬБИЩА
 * ПЕРЕДЕЛАТЬ МАССИВ Place::eachPlaceDateArray() И ВЫВЕСТИ ЕГО С FLEX-DIRECTION: COLUMN
 * ПРИ ЭТОМ ОПРЕДЕЛИТЬ МАКСИМАЛЬНУЮ ВЫСОТУ СТРЕЛЬБИЩА КАК Timetable::BASE_ROW_hEIGHT (40) * количество временных промежутков (с 6 до 24)
 * А ВЫСОТУ КАЖДОГО БЛОКА ОПРЕДЕЛЯТЬ ИЗ МАССИВА eachPlaceDateArray
 *
 * ПРИ ЭТОМ ОПРЕДЕЛИТЬ ЦИКЛ ТАК, ЧТОБЫ ВЫВОДИЛАСЬ ЗАПИСЬ И ЕСЛИ ЕСТЬ ЕЩЕ ОДНА ЗАПИСЬ, НАЛЕЗАЮЩАЯ НА НЕЕ, Т.Е. time_to - time_from
 * ТО ОПРЕДЕЛЯТЬ ЕЕ В ДРУГУЮ КОЛОНКУ
 *

 */







?>
<?php if(($countItem == 0) || ($countItem%$rows == 0)) : ?>
    <div class="row column-row-o">
<?php endif; ?>


<div class="col-sm column-o <?= $result['default'] ? '' : 'temp-column' ?>" id="place-<?= $result['id'] ?>">
    <div class="calendar-column">
        <div class= "calendar-column-header" style="border-bottom: 1px solid <?= $result['background_color'] ?> <?//= $model->getBackgroundColor() ?>;">
            <div class="calendar-column-header-text calendar-column-header-text-o" style="<?= $result['styles'] ?><?//= $model->getColorStyles() ?>">
                <?= $result['name'] ?>
                <a href="#" class="delete-column-o" data-id="<?= $result['id'] ?>" data-date="<?= $result['date'] ?>">
                    <i class="bi bi-file-earmark-excel"></i>
                </a>
            </div>
        </div>

        <style>
            #place-<?= $result['id'] ?> .calendar-column-body {
                width: 100%;
                display: flex;
                flex-direction: column;
                flex-wrap: wrap;
                height: <?= Timetable::BASE_ROW_hEIGHT * $timeGaps ?>px;
                align-content: flex-start;
                flex-flow: column wrap;
            }
            .column-line,
            .column-line,
            .column-calendar-block
            {
                height: <?= Timetable::BASE_ROW_hEIGHT ?>px;
            }

            .column-line {
                display: block;
            }
            .column-calendar-block:first-child {
                background: none;
            }
            #place-<?= $result['id'] ?> .column-time {
                width: 8%;

            }
            #place-<?= $result['id'] ?> .column-content {
                width: <?= count($newTestArray) ? (92 / count($newTestArray)) : 92 ?>%;
                /* 92 - 100% */
            }
        </style>




        <!-- ЗДЕСЬ ВЕСЬ ВЫВОД ЗАПИСЕЙ НА ОПРЕДЕЛЕННУЮ ДАТУ ОПРЕДЕЛЕННОГО СТРЕЛЬБИЩА  -->
        <div class="calendar-column-body">
            <?php foreach(Helper::getTimesArray() as $time) : ?>
            <div class="column-time column-time-o column-line <?= preg_match('/30/', $time) ? 'time-half' : '' ?> <?php if(!User::isInstructor()) : ?>column-line-o column-drag-drop-o<?php endif; ?>" data-time="<?= Helper::formatTimeFromHours($time) ?>" data-date="<?= strtotime($result['date']) ?>" data-place="<?= $result['id'] ?>">
                <div class="column-calendar-block column-calendar-block-time-o">
                    <div class="column-calendar-time ">
                        <span><?= $time ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php foreach($newTestArray as $column => $columnRecords) : ?>
            <?php
                /*echo "<pre>";
                print_r($newTestArray);
                echo "</pre>";
                exit;*/
                ?>
                <?php foreach($columnRecords as $timeRecord => $record) : ?>
                    <?= $this->render('_record', [
                        'column' => $record,
                        'time' => $timeRecord,
                    ]) ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        <!-- // END -->





    </div>
</div>
<?php if((($countItem + 1)%$rows) == 0) : ?>
    </div>
<?php endif; ?>
