<?php
use hesabro\trello\Module;
use yii\helpers\Url;
use yii\helpers\Html;

$attachments= $model->getAttachments()->active()->orderBy('id DESC')->all();
?>
<?php if($attachments): ?>
    <h5><i class="fa fa-paperclip"></i> <?= Yii::t("app","Attachments") ?></h5>

    <?php foreach($attachments as $attach): ?>
        <div class="attachment-thumbnail">
            <div class="attachment-thumbnail-preview" >
                <span class="attachment-thumbnail-preview-ext">
                    <?php
                            $fileUrl = $attach->getFileUrl('attach');
                            if($attach->is_image()){

                                echo Html::img($fileUrl,['class'=>'img-responsive']);
                            }else
                            {
                                echo Html::a('دانلود فایل پیوست', $fileUrl, ['data-pjax' => 0, 'class' => 'btn btn-info']);
                            }
                    ?>
                </span>
            </div>
            <div class="attachment-thumbnail-details" >
                <div class="ltr text-right">
                    <span class="attachment-thumbnail-name"><?= $attach->base_name; ?></span>
                </div>
                <div class="attachment-btn">
                    <a href="<?= $fileUrl ?>" target="_blank"><i class="fa fa-download"></i> <?= Module::t("module","Download") ?></a>
                     <span> / </span>
                    <a href="#" data-ajax-url="<?= Url::to(['attach/delete', 'id'=>$attach->id]) ?>" onclick="return deleteAttach(this);"><i class="fa fa-times"></i> <?= Module::t("module","Delete") ?></a>
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