<?php

use common\models\User;

$columnClass = !User::isInstructor() ? 'column-item-o column-drag-drop-o' : '';

$recordContentInstructor = '';
$recordContentInstructor .= ($column['qty'] ? $column['qty'].' чел. ' : '' ).'<br>';
$recordContentInstructor .= ($column['serviceName'] ? $column['serviceName'] : '' );




$recordContentFull = '';
$recordContentFull .= $column['qty'] ? $column['qty'].' чел. ' : '' ;
$recordContentFull .= $column['name'].'<br>';
$recordContentFull .= $column['serviceName'] ? $column['serviceName'] : '' ;

?>
<?php if($column['fill'] == 'buzy') : ?>
<div class="column-content column-line <?= $column['fill'] ?> <?= $columnClass ?>" data-time="<?= $time  ?>" data-date="<?= $column['date'] ?>" data-id="<?= $column['id'] ?>" data-place="<?= $column['place_id'] ?>" style="<?= $column['styles'] ?>">
    <div class="row">
        <div class="column-calendar-block">
            <div class="column-calendar-time ">
                <span>
                    <?=  User::isInstructor() ? $recordContentInstructor : $recordContentFull ?>
                </span>
                <?php if($column['infinity']) : ?>
                    <span class="infinity">
                        <i class="bi bi-infinity"></i>
                    </span>
                <?php endif; ?>
                <?php if($column['description']) : ?>
                    <span class="text-description">
                        <i class="bi bi-text-center"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else : ?>
    <div class="column-content column-line <?= $column['fill'] ?> column-line-o column-drag-drop-o" data-time="<?= $column['time'] ?>" data-date="<?= $column['date'] ?>" data-place="<?= $column['place_id'] ?>">
        <div class="row">
            <div class="column-calendar-block">
                <div class="column-calendar-time ">

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


