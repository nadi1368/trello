<?php foreach($select_labels as $task_label): ?>
    <span class="card-label <?= 'card-label-'.$task_label->label->color_code; ?> mod-card-front" data-label-id="<?= $task_label->label_id ?>" title="<?= $task_label->label->label_name; ?>"><?= $task_label->label->label_name; ?></span>
<?php endforeach; ?>
