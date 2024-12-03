<?php 
use hesabro\trello\Module;
?>

<?php if($select_members): ?>
    <h5><i class="fa fa-users"></i> <?= Module::t("module","Members") ?></h5>
<?php endif; ?>
<?php foreach($select_members as $member): ?>
        <label class="badge badge-info" title="<?= $member->user->first_name.' '.$member->user->last_name; ?>">
            <?= $member->user->username; ?>
        </label>
<?php endforeach; ?>
