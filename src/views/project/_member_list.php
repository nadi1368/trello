<?php foreach($select_members as $member): ?>
    <label class="badge badge-info" title="<?= $member->user->first_name.' '.$member->user->last_name; ?>">
        <?= $member->user->first_name.' '.$member->user->last_name; ?>
    </label>
<?php endforeach; ?>
