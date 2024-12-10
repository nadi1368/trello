<?php
use yii\widgets\ListView;
use hesabro\trello\Module;
use yii\widgets\Pjax;
?>

<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Module::t("module", "Activities") ?></span>
    <a href="javascript:void(0)" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    <a href="javascript:void(0)" class="pop-over-header-back-btn" onclick="return backMenu(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>

<?php Pjax::begin(); ?>

<div class="pop-over-content" id="project-team-list" style="height: 700px;">
    <div class="list-group without-border rtl">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item_activity',
            'emptyText' => Module::t('module', 'There are no activities to display.'),
            'pager' => [
                'options' => ['class' => 'mt-2 pagination justify-content-center'], 
                'linkOptions' => ['class' => 'page-item page-link'], 
                'activePageCssClass' => 'active',
                'disabledPageCssClass' => 'disabled page-link', 
                'nextPageLabel' => Module::t('module', 'Next'), 
                'prevPageLabel' => Module::t('module', 'Previous'), 
            ],
        ]); ?>
    </div>
</div>

<?php Pjax::end(); ?>