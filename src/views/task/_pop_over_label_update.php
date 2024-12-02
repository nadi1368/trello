<?php

use yii\helpers\Url;
use hesabro\trello\models\TaskLabel;
$colors=TaskLabel::itemAlias('Color');

?>
<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Yii::t("app", "Create Labels") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);">
        <i class="fa fa-times"></i>
    </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backCreateLabel(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content" id="update-label-form">
    <input type="tex" id="title-label-input-update"  class="form-control rtl" placeholder="<?= Yii::t("app","Label Title") ?>">

    <p class="text-danger fade-out rtl" id="msg-label-input-update"><?= Yii::t("app","Please Fill Form") ?></p>
    <h5><?= Yii::t("app","Select Color") ?></h5>
    <?php
    foreach($colors as $title=>$css_class):
        ?>
            <span class="card-label mod-edit-label mod-clickable <?= $css_class; ?>" data-color="<?= $title; ?>" title="<?= $title; ?>">
                <i class="fa"></i>
            </span>
    <?php endforeach; ?>
    <input type="hidden" id="color-label-input-update" value="">
    <input type="hidden" id="id-label-input-update" value="">

    <div class="clearfix"></div>
    <hr/>
    <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['label/update', 'id' => $model->id]) ?>" onclick="return updateLabel(this);"><?= Yii::t("app","Update") ?></a>
    <a class="button button-danger button-inline pull-right"  onclick="return showDeleteLabelForm(this);"><?= Yii::t("app","Delete") ?></a>

    <div class="clearfix"></div>
</div>