<?php
use yii\helpers\Html;
?>
<a class="main-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span> <?= Yii::t("app","Archive List") ?></a>
<ul class="dropdown-menu">
        <?php foreach($archive_statuses as $status): ?>
            <li id=""><?= Html::a($status->title_status,['project-status/restore','id'=>$status->id],['class'=>"restore-archive-this-list",'id'=>'archive-list-'.$status->id]); ?></li>
        <?php endforeach; ?>
</ul>