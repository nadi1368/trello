<?php
use yii\helpers\Url;
?>
<div class="list-group without-border  rtl">
    <?php if($teams): ?>
        <?php foreach($teams as $team): ?>
            <a href="#" class="list-group-item" data-ajax-url="<?= Url::to(['project/add-team', 'id'=>$model->id, 'team_id'=>$team->id]) ?>"   onclick="return teamProject(this);">
                <?= $team->title_team;?>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-warning"><?= Yii::t("app","Not Result") ?></p>
    <?php endif; ?>
</div>