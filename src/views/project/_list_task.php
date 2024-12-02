<?php
use yii\helpers\Html;
use yii\helpers\Url;
use hesabro\trello\models\TaskAssignment;
use hesabro\trello\models\TaskWatches;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\models\Comments;
?>
<?php foreach($status->getProjectTasks()->active()->orderBy('t_order')->all() as $index=>$model): ?>
<li class="task"  id="<?= 'task_'.$model->id; ?>" data-index="<?= $index; ?>" data-ajax-url-move="<?= Url::to(['task/move', 'id'=>$model->id]) ?>" data-ajax-receive-url="<?= Url::to(['task/receive', 'id'=>$model->id]) ?>" data-ajax-url-view="<?= Url::to(['task/view', 'id'=>$model->id]) ?>">
    <div class="list-card-details">
        <div class="list-card-labels js-card-labels">
            <?php
            $select_labels=TaskLabel::find()->active()->findByTask($model->id)->all();
            echo $this->render('_label_list', [
                'select_labels' => $select_labels,
            ]);
            ?>
        </div>
        <span class="list-card-title js-card-name" dir="auto">
            <?= Html::encode($model->title_task); ?>
        </span>
        <div class="list-icon list-task-icon">
            <?php
                echo $model->end ? $model->getDueDate() : '';
            ?>

            <?php
                echo  $model->getCheckListStatus();
            ?>

            <?php
                echo  $model->getNotificationStatus();
            ?>

            <?php
                $watch=TaskWatches::find()->findByTask($model->id)->findByCreator(Yii::$app->user->id)->One();
                echo $watch ? '<span><i class="fa fa-eye" title="'.Yii::t("app","You are watching this card.").'"></i></span>' : '';
            ?>
            <?php
                $count_comment=Comments::find()->findByTask($model->id)->count();
                echo $count_comment>0 ? '<span title="'.Yii::t("app","Comments").'">'.$count_comment.' <i class="fa fa-comments"></i> </span> ' : '';
            ?>
            <?php
                echo $model->desc_task ? '<span><i class="fa fa-list" title="'.Yii::t("app","This card has a description.").'"></i></span>' : '';
            ?>

            <?php
                $count_attach=$model->getAttachments()->active()->count();
                echo $count_attach>0 ? '<span title="'.Yii::t("app","Attachments").'">'.$count_attach.' <i class="fa fa-paperclip"></i> </span> ' : '';
            ?>
        </div>
        <div class="list-card-members">
            <?php
            $select_members=TaskAssignment::find()->active()->findByTask($model->id)->all();
            echo $this->render('_member_list', [
                'select_members' => $select_members,
            ]);
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</li>
<?php endforeach; ?>