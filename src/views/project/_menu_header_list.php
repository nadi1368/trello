<?php
use yii\helpers\Url;
?>
<div class="dropdown">
    <a id="dLabel" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        ...
    </a>
    <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a href="<?= Url::to(['project-status/delete','id'=>$status->id]) ?>" data-delete-list="<?= '#list-'.$status->id; ?>" class="archive-this-list"><?= Yii::t("app","Archive This List") ?></a> </li>
        <li><a href="#"  class=""><?= Yii::t("app","Show Task Archive") ?></a> </li>
    </ul>
</div>