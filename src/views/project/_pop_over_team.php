<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use hesabro\trello\models\Team;
use hesabro\trello\Module;

$model = new Team();
?>
<div id="pop-menu-team" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Module::t("module", "Team") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i>
        </a>
    </div>
    <div class="pop-over-content">
        <?php $form = ActiveForm::begin([
            'action' => ['teams/create', 'project_id' => $project->id],
            'id' => 'create-team-form',
            'options' => ['onsubmit' => "return CreateTeam()"]
        ]); ?>
        <?= $form->field($model, 'title_team')->textInput(['maxlength' => true, 'class' => 'form-control rtl']) ?>
        <p class="text-danger fade-out rtl" id="msg-team-input"><?= Module::t("module", "Please Fill Form") ?></p>
        <hr/>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>