<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\Project;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors=TaskLabel::itemAlias('Color');
?>
<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Module::t("module","Menu") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backMenu(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content"  id="update-project-form">

    <div class="form-input">
        <input type="tex" id="title-board-input" value="<?= $project->project_name; ?>" class="form-control rtl" placeholder="<?= Module::t("module","Board Title") ?>">
    </div>

    <div class="clearfix"></div>

    <div class="form-input">
        <?= Html::dropDownList('status',$project->public_or_private,Project::itemAlias('ShowStatus'),['class'=>'form-control rtl', 'id'=>"public-board-input"]); ?>
    </div>
    <div class="clearfix"></div>

        <?php
        $index=0;
        $input_value="green";
        foreach($colors as $title=>$css_class):
            ?>
            <span class="card-label mod-edit-label mod-clickable <?= $css_class; ?>" data-color="<?= $title; ?>" title="<?= $title; ?>">
                    <?php
                        if($title==$project->color)
                        {
                            echo '<i class="fa fa-check"></i>';
                            $input_value=$title;
                        }else
                        {
                            echo '<i class="fa"></i>';
                        }
                    ?>
            </span>
        <?php endforeach; ?>
        <input type="hidden" id="color-board-input" value="<?= $input_value ?>">

    <div class="clearfix"></div>

    <p class="text-danger fade-out rtl" id="msg-board-input"><?= Module::t("module","Please Fill Form") ?></p>
    <hr/>
    <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['project/update', 'id' => $project->id]) ?>" onclick="return updateProject(this);"><?= Module::t("module","Save") ?></a>
    <div class="clearfix"></div>
</div>