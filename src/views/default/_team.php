<?php
use yii\helpers\Html;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors = TaskLabel::itemAlias('Color');
?>

<div class="title">
    <h3><?= Module::t("module","Teams") ?></h3>
</div>
<?php foreach($teams as $team): ?>
    <div class="col-md-3">
         <?= Html::a($team->team->title_team,['teams/index','id'=>$team->team_id, 'project_id'=>0],['class'=>'team']); ?>
    </div>
<?php endforeach; ?>
<div class="col-md-3 pull-left">
    <div class="content-cal1">
        <div class="avari-info">
            <h2><a href="#" data-toggle="modal" data-target="#createTeamModal"><?= Module::t("module","Create New Team"); ?></a> </h2>
        </div>
    </div>
</div>
<?= $this->render('_create_team') ?>
