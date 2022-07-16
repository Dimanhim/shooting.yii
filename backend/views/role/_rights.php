<?php

use common\models\Role;

?>

<?php

if($roleRights = Role::rolesRights()) : ?>
    <div class="col">
        <div class="roles-rights">
            <h3>Права</h3>
            <table style="width: 100%;">
                <?php foreach($roleRights as $key => $right) : ?>
                    <tr>
                        <td><?= $right ?></td>
                        <td>
                            <?php
                                $options = [
                                    'class' => Role::getLinkClass($key) ? 'text-success' : 'text-dark',
                                ];
                            ?>
                            <a href="#" class="change-permission-o <?= $options['class'] ?>" data-right="<?= $key ?>">
                                <i class="bi bi-check-circle"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php endif; ?>
