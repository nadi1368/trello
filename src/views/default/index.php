<style>
    .c-text-light {
       color: whitesmoke;
    }
</style>

<div class="row">
    <div class="col-md-12 mb-4">
        <?= $this->render('_board', [
            'projects' => $projects
        ]) ?>
    </div>
    <div class="col-md-12">
        <?= $this->render('_team', [
            'teams' => $teams,
        ]) ?>
    </div>
</div>