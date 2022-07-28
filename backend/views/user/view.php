<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'username',
            'email:email',
            'statusName',
            [
                'attribute' => 'right_ids',
                'format' => 'raw',
                'value' => function($data) {
                    if($userRights = $data->rights) {
                        $str = '<ul class="pdl10">';
                        foreach($userRights as $userRight) {
                            if($userRight->rightName) {
                                $str .= "<li>{$userRight->rightName}</li>";
                            }
                        }
                        $str .= '</ul>';
                        return $str;
                    }
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($data) {
                    return date('d.m.Y', $data->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
