<?php
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
            <?= $model->getColumns() ?>

        </div>
    </div>
    <!-- Modal View timetable item -->
    <!--
    to center middle add class modal-dialog-centered to modal-dialog
    -->
    <div id="view-modal">
        <?= $this->render('_modal_view') ?>
        <?= $this->render('_modal_result') ?>
    </div>
    <div id="create-modal"></div>
    <div id="view-modal"></div>
</main>

