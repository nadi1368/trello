<?php
use yii\helpers\Url;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\Module;
?>

<div class="list-group without-border rtl">
    <?php if($labels): ?>
        <?php foreach($labels as $label): ?>
            <?php $task_label=TaskLabel::find()->active()->findByTask($model->id)->findByLabel($label->id)->one(); ?>
            <div class="label-search">
                <a href="#" class="list-group-item  <?= 'card-label-'.$label->color_code; ?>" data-ajax-url="<?= Url::to(['label/toggle', 'id'=>$model->id, 'label_id'=>$label->id]) ?>" data-id="<?= $label->id; ?>" data-task-id="<?= '#task_'.$model->id ?>" data-role="<?= $task_label ? 'select' : 'un-select' ?>" onclick="return labelTask(this);">
                    <i class="<?= $task_label ? 'fa fa-check' : 'fa' ?>"></i>
                    <?= $label->label_name; ?>
                </a>
                <a href="#" class="label-edit"  data-id="<?= $label->id; ?>" data-title="<?= $label->label_name; ?>" data-color="<?= $label->color_code; ?>" data-task-id="<?= '#task_'.$model->id ?>" onclick="return showLabelUpdateForm(this);">
                    <i class="fa fa-pencil"></i>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-warning"><?= Module::t("module","Not Result") ?></p>
    <?php endif; ?>
</div>