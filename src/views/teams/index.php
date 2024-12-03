<?php
use hesabro\trello\Module;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title=$model->title_team;
?>

<?= $this->render('_menu', [
    'model' => $model,
    'project' => $project,
]) ?>
<div class="clearfix"></div>

<div class="col-md-6">
    <div class="content-cal1">
        <div class="views">
            <h3>
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"> </span>
                <?= Module::t("module","Add New User") ?>
            </h3>
        </div>
        <div class="search-box">
            <input type="text" class="form-control" id="serac-user-input" placeholder="<?= Module::t("module","Search User"); ?>" data-ajax-url="<?= Url::to(['teams/search-user', 'team_id'=>$model->id]); ?>">
        </div>
        <div id="result-search-user">
            <?= $this->render('_add_user', [
                'model' => $model,
                'list_user_for_add'=>$list_user_for_add
            ]) ?>

        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="content-cal1">
        <div class="views">
            <h3>
                <span class="glyphicon glyphicon-user" aria-hidden="true"> </span>
                <?= Module::t("module","Users") ?></h3>
        </div>
        <div id="result-list-user">
            <?= $this->render('_list_user', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>

