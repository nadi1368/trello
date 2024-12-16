<?php
use yii\helpers\Html;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors = TaskLabel::itemAlias('Color');
?>

<div class="d-flex justify-content-between m-3">
    <div class="title p-0 m-0">
        <h3 class="p-0 m-0"><?= Module::t("module","Teams") ?></h3>
    </div>
    
    <div class="btn btn-success">
        <h2>
            <a class="c-text-light" href="#" data-toggle="modal" data-target="#createTeamModal"><?= Module::t("module","Create New Team"); ?></a>
        </h2>
    </div>
</div>
        
<?php foreach($teams as $team): ?>
    <div class="col-md-3 c-border-radius">
         <?= Html::a($team->team->title_team,['teams/index','id'=>$team->team_id, 'project_id'=>0],['class'=>'team']); ?>
    </div>
<?php endforeach; ?>

<?= $this->render('_create_team') ?>