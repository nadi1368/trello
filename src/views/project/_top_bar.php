<?php
use yii\widgets\Breadcrumbs;
use hesabro\trello\Module;

$this->title = $project->project_name;
$this->params['breadcrumbs'][] = ['label' => Module::t("module","Projects"), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtl">
    <?= Breadcrumbs::widget([
        'options' => ['class' => 'trello-breadcrumb'],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
</div>
