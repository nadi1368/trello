<?php
use yii\helpers\Html;
use hesabro\trello\models\TaskLogs;
use hesabro\trello\Module;

$activities= TaskLogs::find()->orderBy(['created' => SORT_ASC])->all();
?>

<div class="pop-over-header js-pop-over-header">
    <span class="pop-over-header-title"><?= Module::t("module","Activities") ?></span>
    <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    <a href="#" class="pop-over-header-back-btn" onclick="return backMenu(this);">
        <i class="fa fa-long-arrow-left"></i>
    </a>
</div>

<div class="pop-over-content"  id="project-team-list">
    <div class="list-group without-border rtl">
        <table class="table-member rtl">
            <tbody>
                <?php if($activities): ?>
                    <?php foreach($activities as $log): ?>
                        <tr>
                            <td>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
 