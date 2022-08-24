<ul class="accordion-body accordion-body-o">
<?php if($places = $adress->places) : ?>
    <?php

    foreach($places as $place) :
        $checked = $place->isShowed('places', $place->id) ? 'checked' : '';
        ?>
        <li class="form-check<?= $checked ? ' form-check-input-selected' : '' ?> form-place-check-o" data-adress="<?= $adress->id ?>" data-place="<?= $place->id ?>">
            <input class="form-check-input form-check-input-o" type="checkbox" value="" id="adress-item-<?= $place->id ?>" data-adress="<?= $adress->id ?>" data-id="<?= $place->id ?>" <?= $checked ?>>
            <label class="form-check-label" for="adress-item-<?= $place->id ?>">
                <?= $place->name ?>
            </label>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>
