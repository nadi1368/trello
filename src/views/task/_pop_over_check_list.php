<?php
use yii\helpers\Url;
?>
<div id="pop-menu-check-list" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Yii::t("app","Check List") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <input type="tex" id="title-check-list-input" class="form-control rtl" value="<?= $check_list ? $check_list->title_ch : '' ?>" placeholder="<?= Yii::t("app","Title Check List") ?>">

        <p class="text-danger fade-out rtl" id="msg-check-list-input"><?= Yii::t("app","Please Fill Form") ?></p>
        <div class="clearfix"></div>
        <hr/>

        <div id="create-check-list-btn" class="<?= $check_list ? 'fade-out' : '' ?>">
            <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['check-list/update', 'id' => $model->id]) ?>" onclick="return updateCheckList(this);"><?= Yii::t("app","Create") ?></a>
        </div>
        <div id="update-check-list-btn" class="<?= $check_list ? '' : 'fade-out' ?>">
            <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['check-list/update', 'id' => $model->id]) ?>" onclick="return updateCheckList(this);"><?= Yii::t("app","Update") ?></a>
            <a class="button button-danger button-inline pull-right"  data-ajax-url="<?= Url::to(['check-list/delete', 'id' => $model->id]) ?>" onclick="return deleteCheckList(this);"><?= Yii::t("app","Remove") ?></a>
        </div>

    </div>
</div>