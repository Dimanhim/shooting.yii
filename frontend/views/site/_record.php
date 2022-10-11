<?php

use common\models\User;

$columnClass = !User::isInstructor() ? 'column-item-o' : '';

?>

<div class="col-sm column-calendar-block">
    <div class="calendar-column-body-item <?= $columnClass ?>" data-time="<?= $time  ?>" data-date="<?= $column['date'] ?>"  data-id="<?= $column['id'] ?>" style="<?= $column['styles']  ?>">
        <?=  $column['name'].' '.($column['qty'] ? $column['qty'].' чел. ' : '' ).($column['serviceName'] ? $column['serviceName'] : '' ) ?>
        <?php if($column['infinity']) : ?>
        <span class="infinity">
            <i class="bi bi-infinity"></i>
        </span>
        <?php endif; ?>
    </div>
</div>
