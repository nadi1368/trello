<?php 
use hesabro\trello\Module;
?>

<?php
$css = <<<CSS
    .c-badge {
        display: inline-block;
        margin: 0 4px 4px 0;
        max-width: 100%;
        min-height: 18px;
        overflow: hidden;
        position: relative;
        padding: 1px 2px;
        text-decoration: none;
        text-overflow: ellipsis;
        border-radius: 6px;        
    }
CSS;
$this->registerCSS($css);
?>

<?php if($select_members): ?>
    <h5><i class="fa fa-users"></i> <?= Module::t("module","Members") ?></h5>
<?php endif; ?>
<?php foreach($select_members as $member): ?>
        <label class="c-badge badge-info" title="<?= $member->user->first_name.' '.$member->user->last_name; ?>">
            <?= $member->user->first_name.' '.$member->user->last_name; ?>
        </label>
<?php endforeach; ?>
