<?php

use hesabro\trello\Module;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div id="pop-menu-menu" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Module::t("module", "Menu") ?></span>
        <a href="javascript:void(0)" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <a href="javascript:void(0)" class="menu-button" onclick="return showUpdateProjectForm(this);"><?= Module::t("module", "Update Project"); ?></a>
        <a href="javascript:void(0)" class="menu-button" onclick="return showMemberProject(this);"><?= Module::t("module", "Member"); ?></a>
        <a href="javascript:void(0)" class="menu-button" onclick="return showTeamProject(this);"><?= Module::t("module", "Teams"); ?></a>
        <a href="javascript:void(0)" class="menu-button" onclick="return showActivityProject(this);"><?= Module::t("module", "Activities"); ?></a>

        <?= Html::a(
            Module::t("module", "Activities"),
            'javascript:void(0)',
            [
                'title' => Module::t("module", "Activities"),
                'id' => 'ajax-get-activities',
                'class' => 'nav-link dropdown-item text-black-50',
                'data-size' => 'modal-xl',
                'data-title' => Module::t("module", "Activities"),
                'data-toggle' => 'modal',
                'data-target' => '#modal-pjax',
                'data-url' => Url::to(['ajax-get-activities']),
                // 'disabled' => true,
                // 'data-reload-pjax-container' => 'list-activites-pjax',
            ]
        ); ?>

    </div>
</div>

<div id="pop-menu-update-project" class="hide">
    <?= $this->render('_pop_over_project_update', ['project' => $project]); ?>
</div>

<div id="pop-menu-project-member" class="hide">
    <?= $this->render('_pop_over_project_member', ['project' => $project]); ?>
</div>

<div id="pop-menu-project-team" class="hide">
    <?= $this->render('_pop_over_project_team', ['project' => $project]); ?>
</div>