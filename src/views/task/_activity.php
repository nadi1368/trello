<?php
use yii\helpers\Html;
use hesabro\trello\Module;
$activities=$model->getTaskLogs()->all();
?>

<?php if($activities): ?>
    <h5><i class="fa fa-exchange"></i> <?= Module::t("module","Activity"); ?></h5>
    <?php foreach($activities as $log): ?>
        <div class="panel-comment">
            <div class="comment-body">
                <?= $log->getTitle(); ?>
            </div>
            <div class="comment-footer">
                <ul class="comments-detail">
                    <li><i class="fa fa-user"></i> <?= Html::encode($log->creator->fullName) ?></li>
                    <li><i class="fa fa-calendar"></i> <?= Yii::$app->jdate->date("Y/m/d",$log->created) ?></li>
                    <li><i class="fa fa-clock-o"></i> <?= Yii::$app->jdate->date("H:i:s",$log->created) ?></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
