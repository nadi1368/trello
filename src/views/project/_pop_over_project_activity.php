<?php
use yii\widgets\ListView;
use hesabro\trello\Module;
use yii\widgets\Pjax;
?>


<?php Pjax::begin(['id' => 'list-activites-pjax']); ?>

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