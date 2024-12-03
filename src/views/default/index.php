<?php
use hesabro\trello\Module;

$this->title = Module::t("module", "Boards");
?>

<?= $this->render('_board', [
    'projects' => $projects
]) ?>

<?= $this->render('_team', [
    'teams' => $teams,
]) ?>
