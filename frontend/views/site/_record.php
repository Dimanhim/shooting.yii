<?php

use common\components\Helper;

?>
<div class="col-sm column-calendar-block">
    <div class="calendar-column-body-item column-item-o" data-time="<?= Helper::formatTimeFromHours($time)  ?>" data-date="<?= $column['date'] ?>" style="<?= $column['styles'] ?>">
        <?= $column['name'].' - '.$column['description'] ?>
    </div>
</div>
