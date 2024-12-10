<?php
use hesabro\trello\Module;
use yii\helpers\Html;
?>

<div class="ui <?= Html::encode($project->color); ?>" id="<?= 'main-board-'.$project->id ?>" data-color="<?= Html::encode($project->color); ?>">
    <nav class="navbar app">
        <li role="presentation" class="dropdown" id="menu_archive_list">
            <?= $this->render('_menu_archive_list',['archive_statuses'=>$archive_statuses]); ?>
        </li>

        <li role="notification" class="dropdown" id="menu_notification_list">
            <?= $this->render('_menu_notification_list',['archive_statuses'=>$archive_statuses]); ?>
        </li>
        <li>
            <a href="#" class="icon-list" onclick="return showPopOverTeam(this);" data-pop-id="#pop-menu-menu" title="<?= Module::t("module","Show Menu") ?>"><i class="fa fa-align-justify" data-pop-id="#pop-menu-menu"></i> </a>
        </li>
        <li>
            <a href="#" class="icon-list" onclick="return showPopOverTeam(this);" data-pop-id="#pop-menu-team" title="<?= Module::t("module","Create New Team") ?>"><i class="fa fa-plus" data-pop-id="#pop-menu-team"></i> </a>
        </li>
    </nav>
    <?= $this->render('_top_bar',['project' => $project]); ?>
    <div class="lists" id="lists">
        <?= $this->render('_list',['project'=>$project,'statuses' => $statuses]); ?>
    </div>

    <?= $this->render('_modal'); ?>
    <?= $this->render('_pop_over_team',['project'=>$project]); ?>
    <?= $this->render('_pop_over_right_menu',['project'=>$project]); ?>

</div>
