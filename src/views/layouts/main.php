<?php

/* @var $this View */
/* @var $content string */

use yii\helpers\Html;
use hesabro\trello\bundles\AppAsset;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="main-body">
        <?= $content ?>
    </div>

    <?php
    Modal::begin([
        'headerOptions' => ['id' => 'modalHeaderLoad'],
        'id' => 'modalLoad',
        //keeps from closing modal with esc key or by clicking out of the modal.
        // user must click cancel or X to close
        'clientOptions' => [],
        'options' => ['tabindex' => false]
    ]);
    echo "<div id='modalContentLoad'></div>";
    Modal::end();
    ?>
    <?php
    Modal::begin([
        'headerOptions' => ['id' => 'modalPjaxHeader'],
        'id' => 'modal-pjax',
        'bodyOptions' => [
            'id' => 'modalPjaxContent',
            'class' => 'p-3',
            'data' => ['show-preloader' => 0]
        ],
        'options' => ['tabindex' => false]
    ]); ?>
    <div class="text-center">
        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <?php Modal::end(); ?>


    <?php Pjax::begin(['id' => 'init-pjax']); ?>
    <?php Pjax::end(); ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
