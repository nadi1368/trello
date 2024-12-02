<?php
use yii\helpers\Url;
?>
<div id="pop-menu-dou-date" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Yii::t("app","Dou Date") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <div class="col-md-6">
            <input type="tex" id="date-duedate-input" class="form-control rtl" value="<?= Yii::$app->jdate->date("Y/m/d",$model->end ? $model->end : time()); ?>">
        </div>
        <div class="col-md-6">
            <input type="tex" id="time-duedate-input" class="form-control rtl" value="<?= Yii::$app->jdate->date("H:i",$model->end ? $model->end : time()); ?>">
        </div>
        <p class="text-danger fade-out rtl" id="msg-duedata-input"><?= Yii::t("app","Please Fill Form") ?></p>
        <div class="clearfix"></div>
        <hr/>
        <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['task/due-date-update', 'id' => $model->id]) ?>" onclick="return updateDueDate(this);"><?= Yii::t("app","Save") ?></a>
        <a class="button button-danger button-inline pull-right"  data-ajax-url="<?= Url::to(['task/due-date-delete', 'id' => $model->id]) ?>" onclick="return deleteDueDate(this);"><?= Yii::t("app","Remove") ?></a>
    </div>
</div>