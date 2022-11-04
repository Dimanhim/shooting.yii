<?php

use common\models\User;

$columnClass = !User::isInstructor() ? 'column-item-o' : '';

$recordContentInstructor = '';
$recordContentInstructor .= ($column['qty'] ? $column['qty'].' чел. ' : '' ).'<br>';
$recordContentInstructor .= ($column['serviceName'] ? $column['serviceName'] : '' );




$recordContentFull = '';
$recordContentFull .= $column['qty'] ? $column['qty'].' чел. ' : '' ;
$recordContentFull .= $column['name'].'<br>';
$recordContentFull .= $column['serviceName'] ? $column['serviceName'] : '' ;

?>

<div class="col-sm column-calendar-block">
    <div class="calendar-column-body-item <?= $columnClass ?>" data-time="<?= $time  ?>" data-date="<?= $column['date'] ?>"  data-id="<?= $column['id'] ?>" style="<?= $column['styles']  ?>">
        <?=  User::isInstructor() ? $recordContentInstructor : $recordContentFull ?>
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
