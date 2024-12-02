<?php
use common\components\Menu;
?>
<div class="header-main1">
    <div class=" header-right1">
        <div class="top-nav">
            <nav class="navbar navbar-default">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <?= Menu::widget(
                        [
                            'options' => ['class' => 'nav navbar-nav'],
                            'encodeLabels'=>FALSE,
                            'items' => [
                                ['label' => Yii::t("app","Boards"), 'icon'=>'fa fa-dashboard', 'url' => ['default/index']],
                                ['label' => $project ? $project->project_name : '', 'icon'=>'fa fa-dashboard','visible' => $project, 'url' => ['project/index','p_id'=>$project ? $project->id : 0]],
                                ['label' => $model->title_team, 'icon'=>'fa fa-drivers-license', 'url' => ['teams/index']],
                            ]
                        ]);
                    ?>
                </div>
            </nav>
        </div>
    </div>
</div>