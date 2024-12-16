<?php
use hesabro\trello\Module;
use yii\helpers\Url;
use hesabro\trello\models\TaskAssignment;
?>

<input type="text" 
    id="search-member-input" 
    data-ajax-url="<?= Url::to(['task/search-member', 'id'=>$model->id]) ?>" 
    class="form-control rtl" 
    placeholder="<?= Module::t("module","Search members") ?>"
>

<?php if($members): ?>
    <?php foreach($members as $member): ?>
        <?php $task_assignment=TaskAssignment::find()->active()->findByTask($model->id)->findByUser($member->id)->one(); ?>
        <a href="#" class="list-group-item <?= $task_assignment ? 'active' : '' ?>" data-ajax-url="<?= Url::to(['task/member', 'id'=>$model->id, 'user_id'=>$member->id]) ?>" data-id="<?= $member->id; ?>" data-task-id="<?= '#task_'.$model->id ?>" data-role="<?= $task_assignment ? 'select' : 'un-select' ?>" onclick="return memberTask(this);">
            <i class="<?= $task_assignment ? 'fa fa-check' : 'fa' ?>"></i>
            <?= $member->username.' ( '.$member->first_name.' '.$member->last_name.' )'; ?>
        </a>
    <?php endforeach; ?>
