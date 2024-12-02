<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<div id="pop-menu-menu" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Yii::t("app","Menu") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <a href="#" class="menu-button" onclick="return showUpdateProjectForm(this);"><?= Yii::t("app","Update Project"); ?></a>
        <a href="#" class="menu-button" onclick="return showMemberProject(this);"><?= Yii::t("app","Member"); ?></a>
        <a href="#" class="menu-button" onclick="return showTeamProject(this);"><?= Yii::t("app","Teams"); ?></a>
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