<?php
use yii\helpers\Html;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors = TaskLabel::itemAlias('Color');
?>

<div class="d-flex justify-content-between m-3">
    <div class="title p-0 m-0">
        <h3 class="p-0 m-0"><?= Module::t("module", "Boards"); ?></h3>
    </div>
    
    <div class="btn btn-success">
        <h2>
            <a class="c-text-light" href="#" data-toggle="modal" data-target="#createBoardModal"><?= Module::t("module","Create New Board"); ?></a>
        </h2>
    </div>
</div>

<?php foreach($projects as $project): ?>
    <div class="col-md-3 c-border-radius">
        <?= Html::a($project->project_name, ['project/index','p_id'=>$project->id], ['class'=>'board '.$colors[$project->color]]); ?>
    </div>
<?php endforeach; ?>


<?= $this->render('_create_board') ?>