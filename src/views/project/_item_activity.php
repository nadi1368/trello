<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<a href="javascript:void(0)" class="task"
    id="<?= 'task_' . $model->task->id; ?>"
    data-index="<?= $index; ?>"
    data-ajax-url-move="<?= Url::to(['task/move', 'id' => $model->task->id]) ?>"
    data-ajax-receive-url="<?= Url::to(['task/receive', 'id' => $model->task->id]) ?>"
    data-ajax-url-view="<?= Url::to(['task/view', 'id' => $model->task->id]) ?>"
>
    <div class="panel-comment">
        <div class="comment-body">
            <?= $model->getTitle(); ?>
        </div>
        <div class="comment-footer">
            <ul class="comments-detail">
                <li><i class="fa fa-user"></i> <?= Html::encode($model->creator->fullName) ?></li>
                <li><i class="fa fa-calendar"></i> <?= Yii::$app->jdate->date("Y/m/d", $model->created) ?></li>
                <li><i class="fa fa-clock-o"></i> <?= Yii::$app->jdate->date("H:i:s", $model->created) ?></li>
            </ul>
        </div>
    </div>
</a>