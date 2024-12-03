<?php
use hesabro\trello\Module;
use yii\helpers\Url;
use yii\helpers\Html;
?>

<table class="table table-border">
    <thead>
        <tr>
            <th></th>
            <th><?= Module::t("module","User Name")?></th>
            <th><?= Module::t("module","Full Name")?></th>
            <th><?= Module::t("module","Email")?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($list_user_for_add as $index=>$user): ?>
        <tr>
            <td><?= $index+1; ?></td>
            <td><?= Html::encode($user->username); ?></td>
            <td><?= Html::encode($user->first_name.' '.$user->last_name); ?></td>
            <td><?= Html::encode($user->email); ?></td>
            <td><a href="#" class="btn btn-primary btn-xs btn-flat" title="<?= Module::t("module","Add This User To Team") ?>" data-ajax-url="<?= Url::to(['teams/add-user', 'team_id'=>$model->id, 'user_id'=>$user->id]); ?>" onclick="return AddUser(this);"><span class="glyphicon glyphicon-plus"></span> </a> </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>