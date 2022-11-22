<?php

use common\models\Timetable;

$this->title = 'Расписание';
$dateCache = $model->getDateCash();

?>
<header>
    <div class="header">
        <?= $this->render('_nav', ['user' => $user, 'date' => $dateCache]) ?>
    </div>
</header>
<main>
    <div id="main-o" data-date="<?= $dateCache ?>">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div id="navbarSupportedContent" class="col-sm-2 collapse show column-sidebar-o">
                <?= $this->render('_sidebar', [
                    'model' => $model,
                ]) ?>
            </div>

            <!-- MAIN -->
            <div class="col-sm main-columns-o">
                <?= $model->getColumns() ?>
            </div>
        </div>
    </div>
    <!-- Modal View timetable item -->
    <!--
    to center middle add class modal-dialog-centered to modal-dialog
    -->
    <div id="view-modal"></div>
    <div id="create-modal"></div>
    <div id="view-modal"></div>
    <div id="edit-modal"></div>
</main>
<style>
    .column-line, .calendar-column-body-item {
        height: <?= Timetable::BASE_ROW_hEIGHT ?>px;
    }
    .calendar-column-body-item {
        height: <?= Timetable::getRowHeight(Timetable::BASE_ROW_hEIGHT) ?>px;
    }
</style>

