<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectUser;
use hesabro\trello\Module;

$role_admin=ProjectUser::ROLE_ADMIN;
$is_creator=ProjectUser::YES;
$user_id=Yii::$app->user->id;
?>
<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Module::t("module","Members") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backMenu(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content"  id="project-member-list">
    <div class="list-group without-border  rtl">
        <table class="table-member rtl">
            <tbody>
                <?php foreach($project->getProjectUsers()->active()->all() as $member): ?>
                    <tr>
                        <td width="20px">
                            <?php if($member->is_creator!==$is_creator && $project->isAdmin($user_id)): ?>
                                <a href="#" class="delete" data-ajax-url="<?= Url::to(['project/remove-member', 'id'=>$project->id, 'project_user'=>$member->id]) ?>" title="<?= Module::t("module","Delete This Member") ?>"  onclick="return memberProject(this);">
                                    <i class="fa fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $member->user->username.' ( '.$member->user->first_name.' '.$member->user->last_name.' )'; ?>
                        </td>
                        <td width="20px">
                            <?php if($member->is_creator==$is_creator )://اگر ایجاد کننده پروژه باشد کسی اجازه تغیر رل را ندارد ?>
                                <span class="is_admin"  title="<?= Module::t("module","Role Is Admin") ?>">
                                    <i class="fa fa-check-square-o"></i>
                                </span>
                            <?php elseif($project->isAdmin($user_id)):// اگر کاربر سطح دسترسی ادمین داشت ?>
                                    <?php if($member->role==$role_admin): ?>
                                        <a href="#" class="is_admin" title="<?= Module::t("module","Change Role To User") ?>" data-ajax-url="<?= Url::to(['project/change-role-member', 'id'=>$project->id, 'project_user'=>$member->id]) ?>"  onclick="return changeRoleProjectMember(this);">
                                            <i class="fa fa-check-square-o"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="#" class="is_user" title="<?= Module::t("module","Change Role To Admin") ?>" data-ajax-url="<?= Url::to(['project/change-role-member', 'id'=>$project->id, 'project_user'=>$member->id]) ?>"  onclick="return changeRoleProjectMember(this);">
                                            <i class="fa fa-square-o"></i>
                                        </a>
                                    <?php endif; ?>
                            <?php else: ?>
                                <?php if($member->role==$role_admin): ?>
                                    <span class="is_admin" title="<?= Module::t("module","Role Is Admin") ?>">
                                        <i class="fa fa-check-square-o"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="is_user" title="<?= Module::t("module","Role Is User") ?>">
                                        <i class="fa fa-square-o"></i>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr />
        <input type="tex" id="search-project-member-input" data-ajax-url="<?= Url::to(['project/search-member', 'id'=>$project->id]) ?>" class="form-control rtl" placeholder="<?= Module::t("module","Search Members") ?>">
        <div id="result-project-member">

        </div>
    </div>
</div>