<?php
use yii\helpers\Url;
?>
<div id="pop-menu-memeber" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Yii::t("app","Members") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <input type="tex" id="search-member-input" data-ajax-url="<?= Url::to(['task/search-member', 'id'=>$model->id]) ?>" class="form-control rtl" placeholder="<?= Yii::t("app","Search members") ?>">
        <div id="result-member">
            <?= $this->render('_member',['members'=>$members, 'model'=>$model]); ?>
        </div>
    </div>
</div>