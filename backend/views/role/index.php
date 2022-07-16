<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped table-bordered">
        <tr>
            <td>
                №
            </td>
            <td>
                Название
            </td>
            <td>
                Описание
            </td>
            <td>
                Действия
            </td>
        </tr>
        <?php if($roles) : ?>
            <?php $count = 1; foreach($roles as $role) : ?>
                <tr>
                    <td>
                        <?= $count ?>
                    </td>
                    <td class="role-row">
                        <a href="" class="role-name">
                            <?= $role->name ?>
                        </a>
                        <ul>
                            <li>
                                <a href="">
                                    test@test.ru
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    test1@test1.ru
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    test2@test2.ru
                                </a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <?= $role->description ?>
                    </td>
                    <td>
                        <a href="<?= Url::to(['role/update', 'role' => $role->name]) ?>">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="<?= Url::to(['role/delete']) ?>" class="delete-role-o" data-role="<?= $role->name ?>" data-confirm="Вы уверены, что хотите удалить роль?">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php $count++; endforeach; ?>
        <?php else : ?>

        <?php endif; ?>
    </table>


</div>
