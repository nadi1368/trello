<?php
use yii\helpers\Url;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;

$colors=TaskLabel::itemAlias('Color');
?>

<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Module::t("module", "Create Labels") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);">
        <i class="fa fa-times"></i>
    </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backCreateLabel(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>
<div class="pop-over-content" id="create-label-form">
    <input type="tex" id="title-label-input"  class="form-control rtl" placeholder="<?= Module::t("module","Label Title") ?>">

    <p class="text-danger fade-out rtl" id="msg-label-input"><?= Module::t("module","Please Fill Form") ?></p>
    <h5><?= Module::t("module","Select Color") ?></h5>
    <?php
    $index=0;
    $input_value="green";
    foreach($colors as $title=>$css_class):
        ?>
        <span class="card-label mod-edit-label mod-clickable <?= $css_class; ?>" data-color="<?= $title; ?>" title="<?= $title; ?>">
            <?php
                if($index++==0)
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
    <input type="hidden" id="color-label-input" value="<?= $input_value ?>">

    <div class="clearfix"></div>
    <hr/>
    <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['label/create', 'id' => $model->id]) ?>" onclick="return createLabel(this);">
        <?= Module::t("module","Create") ?><
    /a>
    <div class="clearfix"></div>
</div>