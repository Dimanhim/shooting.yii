<?php

use yii\helpers\Url;

?>
<a href="<?= Url::to(['site/logout']) ?>" class="sign-up-user-link">
    (<?= $user->name ? $user->name : $user->username ?>)
    <i class="bi bi-box-arrow-right"></i>
</a>
<!--
<a href="">
    <i class="bi bi-box-arrow-right"></i>
</a>
-->
