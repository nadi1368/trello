<?php
use yii\helpers\Url;
use yii\helpers\Html;
$attachments=$model->getAttachments()->active()->orderBy('id DESC')->all();
?>
<?php if($attachments): ?>
    <h5><i class="fa fa-paperclip"></i> <?= Yii::t("app","Attachments") ?></h5>

    <?php foreach($attachments as $attach): ?>
        <div class="attachment-thumbnail">
            <div class="attachment-thumbnail-preview" >
                <span class="attachment-thumbnail-preview-ext">
                    <?php
                            if($attach->is_image()){

                                echo Html::img($attach->getUploadDir().$attach->attach,['alt'=>$attach->base_name, 'class'=>'img-responsive']);
                            }else
                            {
                                echo pathinfo($attach->base_name,PATHINFO_EXTENSION);
                            }
                    ?>
                </span>
            </div>
            <div class="attachment-thumbnail-details" >
                <div class="ltr text-right">
                    <span class="attachment-thumbnail-name"><?= $attach->base_name; ?></span>
                </div>
                <div class="attachment-btn">
                    <a href="<?= $attach->getUploadDir().$attach->attach ?>" target="_blank"><i class="fa fa-download"></i> <?= Yii::t("app","Download") ?></a>
                     <span> / </span>
                    <a href="#" data-ajax-url="<?= Url::to(['attach/delete', 'id'=>$attach->id]) ?>" onclick="return deleteAttach(this);"><i class="fa fa-times"></i> <?= Yii::t("app","Delete") ?></a>
                </div>
                <ul class="attachment-footer">
                    <li><i class="fa fa-user"></i> <?= Html::encode($attach->creator->fullName) ?></li>
                    <li><i class="fa fa-calendar"></i> <?= Yii::$app->jdate->date("Y/m/d",$attach->created) ?></li>
                    <li><i class="fa fa-clock-o"></i> <?= Yii::$app->jdate->date("H:i:s",$attach->created) ?></li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>