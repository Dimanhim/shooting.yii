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
            <?php for($i = 0; $i < 3; $i++) : ?>
                <?= $this->render('_column') ?>
            <?php endfor; ?>

        </div>
    </div>
</main>

