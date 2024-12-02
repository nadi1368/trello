<?php
use yii\widgets\Breadcrumbs;

$this->title = $project->project_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t("app","Projects"), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtl">
    <?= Breadcrumbs::widget([
        'options' => ['class' => 'trello-breadcrumb'],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
</div>
