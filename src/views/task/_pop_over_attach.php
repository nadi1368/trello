<?php
use yii\helpers\Url;
?>
<div id="pop-menu-attach" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Yii::t("app","Attachment") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <form>
            <div id="dropBox" data-ajax-url="<?= Url::to(['attach/create', 'id' => $model->id]) ?>">
                <p><?= Yii::t("app","Select file to upload") ?></p>
            </div>
            <input type="file" name="fileInput" id="fileInput" />
        </form>
    </div>
</div>