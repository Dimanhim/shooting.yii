<?php if($model) : foreach($model as $value) : ?>
    <?= $this->render('_column', ['model' => $value, 'date' => $date]) ?>
<?php endforeach; endif; ?>
