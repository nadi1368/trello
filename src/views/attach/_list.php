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
                           // $fileUrl = $attach->getFileUrl('attach');
                            $fileUrl = 'https://storage.hesabro.ir/trello/1/2a8b7361329ee356b336ec638a74e78f.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=f5169aec-6fce-42b3-9c7e-25f3ac9a790f%2F20241205%2Firan%2Fs3%2Faws4_request&X-Amz-Date=20241205T144349Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1800&X-Amz-Signature=515382d309fbf1a6d3f06002b1be660556763d0fa3b6529794666b386257e66c';
                            if($attach->is_image()){

                                echo Html::a(Html::img($fileUrl,['class'=>'img-responsive', 'width'=>'100px']), ['attach/view', 'id'=>$attach->id], ['data-pjax' => 0, 'class'=> 'showModalButton']);
                            }else
                            {
                                echo Html::a('دانلود فایل پیوست', $fileUrl, ['data-pjax' => 0, 'class' => 'btn btn-info']);
                            }
                    ?>
                </span>
            </div>
            <div class="attachment-thumbnail-details rtl">
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