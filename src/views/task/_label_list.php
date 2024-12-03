<?php
use hesabro\trello\Module;
?>

<?php if($select_labels): ?>
    <h5><i class="fa fa-tags"></i> <?= Module::t("module","Labels") ?></h5>
<?php endif; ?>
<?php foreach($select_labels as $task_label): ?>
    <label class="label <?= 'card-label-'.$task_label->label->color_code; ?>">
        <?= $task_label->label->label_name; ?>
    </label>
<?php endforeach; ?>
