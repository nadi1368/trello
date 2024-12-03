<?php
use yii\helpers\Url;
use hesabro\trello\Module;
?>
<div class="dropdown">
    <a id="dLabel" data-target="#" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        ...
    </a>
    <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a href="<?= Url::to(['project-status/delete','id'=>$status->id]) ?>" data-delete-list="<?= '#list-'.$status->id; ?>" class="archive-this-list"><?= Module::t("module","Archive This List") ?></a> </li>
        <li><a href="#"  class=""><?= Module::t("module","Show Task Archive") ?></a> </li>
    </ul>
</div>