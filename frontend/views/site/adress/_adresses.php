<?php

use common\models\Street;

?>
<div>
    <div class="accordion">
        <?php if($adresses = Street::find()->all()) : ?>
            <?php
            foreach($adresses as $adress) :
                $classCollapse = $adress->isShowedPlaces() ? ['collapsed' => '', 'show' => 'show'] : ['collapsed' => 'collapsed', 'show' => '']
                ?>

                <div class="accordion-item accordion-item-o accordion-address-item-o" data-id="<?= $adress->id ?>">
                    <h2 class="accordion-header">
                        <button class="accordion-button accordion-button-o <?= $classCollapse['collapsed'] ?>" type="button" data-bs-toggle="collapse" data-bs-target="#panel-action-adress-<?= $adress->id ?>" aria-expanded="true" aria-controls="panel-action-adress-<?= $adress->id ?>">
                            <?= $adress->name ?>
                        </button>
                    </h2>
                    <div id="panel-action-adress-<?= $adress->id ?>" class="accordion-collapse accordion-collapse-o collapse <?= $classCollapse['show'] ?>" aria-labelledby="panel-action-adress-<?= $adress->id ?>" data-id="<?= $adress->id ?>">

                            <?= $this->render('_places', [
                                'adress' => $adress
                            ]) ?>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
