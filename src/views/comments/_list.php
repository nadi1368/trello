<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\Module;
?>
<?php if($comments): ?>
    <h5><i class="fa fa-comments"></i> <?= Module::t("module","Comments") ?></h5>

        <?php foreach($comments as $comment): ?>
            <div class="panel-comment">
                <div class="comment-body">
                    <?= nl2br(Html::encode($comment->title_comment));?>
                </div>
                <div class="comment-footer">
                    <ul class="comments-detail">
                        <li><i class="fa fa-user"></i> <?= Html::encode($comment->creator->fullName) ?></li>
                        <li><i class="fa fa-calendar"></i> <?= Yii::$app->jdate->date("Y/m/d",$comment->created) ?></li>
                        <li><i class="fa fa-clock-o"></i> <?= Yii::$app->jdate->date("H:i:s",$comment->created) ?></li>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
<?php endif; ?>
<div class="new-comment rtl">
    <h5><i class="fa fa-comment"></i> <?= Module::t("module","Add Comment") ?></h5>
    <textarea class="form-control rtl comment-box-input js-new-comment-input" placeholder="Write a comment…" tabindex="1" dir="auto" id="title-comment-input" style="overflow: hidden; word-wrap: break-word; height: 75px;resize:none;word-wrap:break-word;"></textarea>
    <p class="text-danger fade-out rtl" id="msg-comments-input"><?= Module::t("module","Please Fill Form") ?></p>
    <a href="#" class="button button-success button-inline" data-ajax-url="<?= Url::to(['comments/create', 'id' => $model->id]) ?>" onclick="return createComments(this);"><?= Module::t("module","Save") ?></a>
</div>