<?php if($model) : foreach($model as $value) : ?>
    <?= $this->render('_column', ['model' => $value]) ?>
<?php endforeach; endif; ?>
