<?php
$this->title = 'Расписание';
$dateCache = $model->getDateCash();
$array_cache = [
        [
            'place_id' => 1,
            'date' => '28.07.2022',
        ],
        [
            'place_id' => 2,
            'date' => '30.07.2022',
        ],
];
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
    <div id="view-modal">
        <?//= $this->render('_modal_view') ?>
        <?//= $this->render('_modal_result') ?>
    </div>
    <div id="create-modal"></div>
    <div id="view-modal"></div>
</main>

