<?php

/* @var $this View */
/* @var $content string */

use hesabro\helpers\components\Env;
use yii\helpers\Html;
use hesabro\trello\bundles\AppAsset;
use yii\web\View;
use common\widgets\dateRangePicker\RangePickerAsset;
use yii\bootstrap\Modal;

$socketServer = Env::get('TRELLO_SOCKET_SERVER');
$this->registerJs(<<<JS
const socketServer = '$socketServer';
JS, View::POS_BEGIN);

AppAsset::register($this);
RangePickerAsset::register($this);
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

    <?php $this->endBody() ?>
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
</body>
</html>
<?php $this->endPage() ?>
