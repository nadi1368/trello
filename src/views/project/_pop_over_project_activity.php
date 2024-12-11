<?php
use yii\widgets\ListView;
use hesabro\trello\Module;
?>


<div  >
    <div class="list-group without-border rtl">
        <?php \yii\widgets\Pjax::begin(['timeout' => 30000, 'enablePushState' => false]); ?>
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
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>
