<?php
use yii\helpers\Html;
use hesabro\trello\models\Notifications;
?>
<a class="main-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span> <?= Yii::t("app","Notification") ?></a>
<ul class="dropdown-menu notification">
    <?php foreach(Notifications::getMenuItem() as $item): ?>
        <li><b><?= $item->update->fullName; ?></b> <?= $item->title_not; ?></li>
    <?php endforeach; ?>
</ul>