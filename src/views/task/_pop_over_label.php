<?php
use yii\helpers\Url;
use hesabro\trello\Module;
?>

<div id="pop-menu-label" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Module::t("module", "Labels") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);">
            <i class="fa fa-times"></i>
        </a>
    </div>
    <div class="pop-over-content">
        <input type="tex" id="search-label-input" data-ajax-url="<?= Url::to(['label/search', 'id' => $model->id]) ?>" class="form-control rtl" placeholder="<?= Module::t("module","Search label") ?>">
        <div id="result-label">
            <?= $this->render('_labels_list_search',['labels'=>$labels, 'model'=>$model]); ?>
        </div>
        <div class="clearfix"></div>
        <a class="quiet-button full js-toggle-color-blind-mode" href="#" onclick="return showCreateLabelForm(this);">
            <?= Module::t("module", "Create a new label") ?>
        </a>
    </div>
</div>

<div id="pop-menu-create-label" class="hide">
    <?= $this->render('_pop_over_label_create',['model'=>$model]); ?>
</div>

<div id="pop-menu-update-label" class="hide">
    <?= $this->render('_pop_over_label_update',['model'=>$model]); ?>
</div>

<div id="pop-menu-delete-label" class="hide">
    <?= $this->render('_pop_over_label_delete',['model'=>$model]); ?>
</div>

