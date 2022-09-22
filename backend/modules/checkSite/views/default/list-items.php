<?php

use yii\helpers\Html;
use yii\base\View;

?>

<?php foreach ($items as $model) { ?>

    <?php if (count($model->childs)) { ?>
        <div class="section">
            <?= View::render('item', ['model' => $model, 'isSection' => true]) ?>

            <div class="block">
                <?= View::render('item', ['model' => $model, 'isSection' => false]) ?>
                <?= View::render('list-items', ['items' => $model->childs]) ?>
            </div>
        </div>
    <?php } else { ?>
        <?= View::render('item', ['model' => $model, 'isSection' => false]) ?>
    <?php } ?>
<?php } ?>
