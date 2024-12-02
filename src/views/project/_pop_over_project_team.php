<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectUser;

$role_admin=ProjectUser::ROLE_ADMIN;
$is_creator=ProjectUser::YES;
$user_id=Yii::$app->user->id;
?>
<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Yii::t("app","Teams") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backMenu(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content"  id="project-team-list">
    <div class="list-group without-border  rtl">
        <table class="table-member rtl">
            <tbody>
            <?php foreach($project->getProjectTeams()->active()->all() as $team): ?>
                <tr>
                    <td width="20px">
                        <?php if($project->isAdmin($user_id)): ?>
                            <a href="#" class="delete" data-ajax-url="<?= Url::to(['project/remove-team', 'id'=>$project->id, 'project_team'=>$team->id]) ?>" title="<?= Yii::t("app","Delete This Team") ?>"  onclick="return teamProject(this);">
                                <i class="fa fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $team->team->title_team; ?>
                    </td>
                    <td width="20px">
                        <a href="<?= Url::to(['teams/index', 'id'=>$team->team->id, 'project_id'=>$project->id]) ?>"  title="<?= Yii::t("app","View This Team") ?>" >
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <hr />
        <input type="tex" id="search-project-team-input" data-ajax-url="<?= Url::to(['project/search-team', 'id'=>$project->id]) ?>" class="form-control rtl" placeholder="<?= Yii::t("app","Search Team") ?>">
        <div id="result-project-team">

        </div>
    </div>
</div>