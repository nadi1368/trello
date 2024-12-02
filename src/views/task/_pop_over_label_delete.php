<?php
use yii\helpers\Url;
?>
<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Yii::t("app", "Create Labels") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);">
        <i class="fa fa-times"></i>
    </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backUpdateLabel(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content" id="delete-label-form">
    <div class="alert">
        <p class="text-info rtl"><?= Yii::t('app', 'Are you sure you want to delete this item?') ?></p>
        <hr/>
        <input type="hidden" id="title-label-input-delete" value="">
        <input type="hidden" id="id-label-input-delete" value="">
        <a class="button button-danger text-center"  data-ajax-url="<?= Url::to(['label/delete', 'id' => $model->id]) ?>"  onclick="return deleteLabel(this);"><?= Yii::t("app","Delete") ?></a>
    </div>
</div>
