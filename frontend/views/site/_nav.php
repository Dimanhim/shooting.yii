<?php

use yii\helpers\Url;
use common\components\Helper;

?>
<nav class="header-top fixed-top navbar-light bg-light">
    <div class="container-fluid">
        <div class="header-items">
            <div class="header-item">
                <!-- Кнопка показа меню -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- END Кнопка показа меню -->

                <!-- Всплывающее скрытое меню -->
                <!--
                <div id="navbarSupportedContent" class="collapse navbar-collapse header-menu-content">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                </div>
                -->
                <!-- END Всплывающее скрытое меню -->
            </div>

            <div class="header-item header-item-sign-up-mobile">

                <?= $this->render('blocks/_sign_up', ['user' => $user]) ?>

            </div>

            <div class="header-item">
                <a href="#" class="btn btn-primary new-event-o">
                    <i class="bi bi-calendar-event"></i>
                    Новое событие
                </a>
            </div>

            <div class="header-item btn-change-date">
                <a href="<?= Url::to(['site/set-new-date']) ?>" id="btn-change-date">
                    <i class="bi bi-calendar-check"></i>
                    <span class="date-name">
                        Сегодня
                    </span>
                </a>
                <!--
                <ul class="change-date-menu">
                    <li>
                        <a href="" data-val="1">
                            Сегодня
                        </a>
                    </li>
                </ul>
                -->
            </div>
            <div class="header-item not-styles-link">
                <a href="<?= Url::to(['site/set-new-date', 'action' => 'minus']) ?>">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <a href="<?= Url::to(['site/set-new-date', 'action' => 'plus']) ?>">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="header-item">
                <?= Helper::getDateFormatHeader($date) ?>
            </div>


            <!--
            <div class="header-item header-item-right">
                <div class="header-calendar-day btn-change-date">
                    <i class="bi bi-calendar-check"></i>
                    <input id="select-date" class="select-date-input" type="text" value="День">
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
            -->
            <div class="header-item header-item-sign-up header-item-right">

                <?= $this->render('blocks/_sign_up', ['user' => $user]) ?>

            </div>
        </div>
    </div>
</nav>
