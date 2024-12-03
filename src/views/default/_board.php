<?php
use yii\helpers\Html;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors = TaskLabel::itemAlias('Color');
?>

<?php foreach($projects as $project): ?>
    <div class="col-md-3">
        <?= Html::a($project->project_name, ['project/index','p_id'=>$project->id], ['class'=>'board '.$colors[$project->color]]); ?>
    </div>
<?php endforeach; ?>
    <div class="col-md-3 pull-left">
        <div class="content-cal1">
            <div class="avari-info">
                <h2><a href="#" data-toggle="modal" data-target="#createBoardModal"><?= Module::t("module","Create New Board"); ?></a></h2>
            </div>
        </div>
    </div>
<?= $this->render('_create_board') ?>