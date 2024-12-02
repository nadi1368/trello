<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\TeamUsers;
$this->title=$model->title_team;

?>
<table class="table table-border">
    <thead>
    <tr>
        <th></th>
        <th><?= Yii::t("app","User Name")?></th>
        <th><?= Yii::t("app","Full Name")?></th>
        <th><?= Yii::t("app","Email")?></th>
        <th><?= Yii::t("app","Role") ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($model->getTeamUsers()->active()->all() as $index=>$team_user): ?>
        <tr>
            <td><?= $index+1; ?></td>
            <td><?= Html::encode($team_user->user->username); ?></td>
            <td><?= Html::encode($team_user->user->first_name.' '.$team_user->user->last_name); ?></td>
            <td><?= Html::encode($team_user->user->email); ?></td>
            <td>
                <?php if($team_user->is_creator==TeamUsers::NO): ?>
                    <?= Html::dropDownList('role',$team_user->role,TeamUsers::itemAlias('Role'),['class'=>'form-control input-sm','onchange'=>'return changeRole(this);','data-ajax-url'=>Url::to(['teams/change-role-user', 'team_id'=>$model->id, 'teamUser_id'=>$team_user->id])]); ?>
                <?php else: ?>
                    <label class="badge badge-success"><?= TeamUsers::itemAlias('Role',$team_user->role); ?></label>
                <?php endif; ?>
            </td>
            <td>
                <?php if($team_user->is_creator==TeamUsers::NO): ?>
                    <a href="#" class="btn btn-danger btn-xs btn-flat" title="<?= Yii::t("app","Delete This User From Team") ?>" data-ajax-url="<?= Url::to(['teams/delete-user', 'team_id'=>$model->id, 'teamUser_id'=>$team_user->id]); ?>" onclick="return DeleteUser(this);"><span class="glyphicon glyphicon-remove"></span> </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>