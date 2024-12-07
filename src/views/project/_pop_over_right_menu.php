<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\Module;
?>

<div id="pop-menu-menu" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Module::t("module","Menu") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <a href="#" class="menu-button" onclick="return showUpdateProjectForm(this);"><?= Module::t("module","Update Project"); ?></a>
        <a href="#" class="menu-button" onclick="return showMemberProject(this);"><?= Module::t("module","Member"); ?></a>
        <a href="#" class="menu-button" onclick="return showTeamProject(this);"><?= Module::t("module","Teams"); ?></a>
        <a href="#" class="menu-button" onclick="return showActivityProject(this);"><?= Module::t("module","Activities"); ?></a>
    </div>
</div>

<div id="pop-menu-update-project" class="hide">
    <?= $this->render('_pop_over_project_update',['project'=>$project]); ?>
</div>

<div id="pop-menu-project-member" class="hide">
    <?= $this->render('_pop_over_project_member',['project'=>$project]); ?>
</div>

<div id="pop-menu-project-team" class="hide">
    <?= $this->render('_pop_over_project_team',['project'=>$project]); ?>
</div>

<div id="pop-menu-project-activity" class="hide">
    <?= $this->render('_pop_over_project_activity',['project'=>$project]); ?>
</div>