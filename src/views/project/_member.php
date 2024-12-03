<?php
use yii\helpers\Url;
use hesabro\trello\Module;
?>
<div class="list-group without-border  rtl">
    <?php if($members): ?>
        <?php foreach($members as $member): ?>
        <a href="#" class="list-group-item" data-ajax-url="<?= Url::to(['project/add-member', 'id'=>$model->id, 'user_id'=>$member->id]) ?>"   onclick="return memberProject(this);">
            <?= $member->username.' ( '.$member->first_name.' '.$member->last_name.' )'; ?>
        </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-warning"><?= Module::t("module","Not Result") ?></p>
    <?php endif; ?>
</div>