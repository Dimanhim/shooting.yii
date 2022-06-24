<?php
$this->title = 'Расписание';
?>
<header>
    <div class="header">
        <?= $this->render('_nav') ?>
    </div>
</header>
<main>
    <div id="main">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-sm-2">
                <?= $this->render('_sidebar') ?>
            </div>

            <!-- MAIN -->
            <?= $model->getColumns() ?>
        </div>
    </div>
    <!-- Modal View timetable item -->
    <!--
    to center middle add class modal-dialog-centered to modal-dialog
    -->
    <?= $this->render('_modal_view') ?>
</main>

