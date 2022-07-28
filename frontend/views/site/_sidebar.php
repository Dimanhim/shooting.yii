<?php

use common\models\Street;

?>

<!-- CALENDAR -->
<div class="panel-block">
    <div class="panel-date">
        <div class="panel-datepicker" id="panel-datepicker"></div>
    </div>
</div>

<!-- EMAIL -->
<!--
<div class="panel-block">
    <div class="panel-block-email">
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel-email" aria-expanded="true" aria-controls="panel-email">
                        e.cherepov@vistrel.ru
                    </button>
                </h2>

                <div id="panel-email" class="accordion-collapse collapse" aria-labelledby="panel-email">
                    <div class="accordion-body">
                        <ul class="emails-list btn-change-date emails-list-o">
                            <li>
                                <a href="#">e.cherepov2@vistrel.ru</a>
                            </li>
                            <li>
                                <a href="#">e.cherepov3@vistrel.ru</a>
                            </li>
                            <li>
                                <a href="#">e.cherepov4@vistrel.ru</a>
                            </li>
                            <li>
                                <a href="#">e.cherepov5@vistrel.ru</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
-->

<!-- ADRESSES -->
<div class="panel-block">
    <div class="accordion">
        <?php if($adresses = Street::find()->all()) : ?>
            <?php
                foreach($adresses as $adress) :
                    $classCollapse = $adress->isShowedPlaces() ? ['collapsed' => '', 'show' => 'show'] : ['collapsed' => 'collapsed', 'show' => '']
            ?>

                <div class="accordion-item accordion-item-o accordion-address-item-o">
                    <h2 class="accordion-header">
                        <button class="accordion-button accordion-button-o <?= $classCollapse['collapsed'] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-adress-<?= $adress->id ?>" aria-expanded="true" aria-controls="panel-action-adress-<?= $adress->id ?>">
                            <?= $adress->name ?>
                        </button>
                    </h2>
                    <div id="panel-action-adress-<?= $adress->id ?>" class="accordion-collapse accordion-collapse-o collapse <?= $classCollapse['show'] ?>" aria-labelledby="panel-action-adress-<?= $adress->id ?>">
                        <div class="accordion-body accordion-body-o">
                            <?php if($places = $adress->places) : ?>
                                <?php

                                    foreach($places as $place) :
                                        $checked = $place->isShowed('places', $place->id) ? 'checked' : '';

                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input form-check-input-o" type="checkbox" value="" id="adress-item-<?= $place->id ?>" data-id="<?= $place->id ?>" <?= $checked ?>>
                                        <label class="form-check-label" for="adress-item-<?= $place->id ?>">
                                            <?= $place->name ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php if($model->_user->getRight(\common\models\UserRight::RIGHT_2)) : ?>
<!-- MY CALENDARS -->
<div class="panel-block">
    <div class="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-my-calendar-1" aria-expanded="true" aria-controls="panel-action-my-calendar-1">
                    Мои календари
                </button>
            </h2>
            <div id="panel-action-my-calendar-1" class="accordion-collapse collapse" aria-labelledby="panel-action-my-calendar-1">
                <div class="accordion-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="my-calendar-1">
                        <label class="form-check-label" for="my-calendar-1">
                            Календарь 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="my-calendar-2" checked>
                        <label class="form-check-label" for="my-calendar-2">
                            Календарь 2
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- OTHER CALENDARS -->
<div class="panel-block">
    <div class="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-other-calendar-1" aria-expanded="true" aria-controls="panel-action-other-calendar-1">
                    Другие календари
                </button>
            </h2>
            <div id="panel-action-other-calendar-1" class="accordion-collapse collapse" aria-labelledby="panel-action-other-calendar-1">
                <div class="accordion-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="other-calendar-1">
                        <label class="form-check-label" for="other-calendar-1">
                            Календарь 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="other-calendar-2" checked>
                        <label class="form-check-label" for="other-calendar-2">
                            Календарь 2
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- USER CALENDARS -->
<div class="panel-block">
    <div class="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-user-calendar-1" aria-expanded="true" aria-controls="panel-action-user-calendar-1">
                    Календари пользователя
                </button>
            </h2>
            <div id="panel-action-user-calendar-1" class="accordion-collapse collapse" aria-labelledby="panel-action-user-calendar-1">
                <div class="accordion-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="user-calendar-1">
                        <label class="form-check-label" for="user-calendar-1">
                            Календарь 1
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="user-calendar-2" checked>
                        <label class="form-check-label" for="user-calendar-2">
                            Календарь 2
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GROUPS -->
<div class="panel-block">
    <div class="accordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-users-1" aria-expanded="true" aria-controls="panel-action-users-1">
                    Группы
                </button>
            </h2>
            <div id="panel-action-users-1" class="accordion-collapse collapse" aria-labelledby="panel-action-users-1">
                <div class="accordion-body">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex hic ratione repellendus. Aspernatur atque consectetur dolores harum, labore, laboriosam mollitia quae qui repellat totam ullam unde voluptas voluptatum? Consequatur, dolorem!
                </div>
            </div>
        </div>
    </div>
</div>

