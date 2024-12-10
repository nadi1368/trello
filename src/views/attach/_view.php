<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model hesabro\trello\models\Attachments */
?>
<div class="card">
    <div class="card-body">
        <?php
        // $fileUrl = $model->getFileUrl('attach');
        $fileUrl = 'https://storage.hesabro.ir/trello/1/2a8b7361329ee356b336ec638a74e78f.png?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=f5169aec-6fce-42b3-9c7e-25f3ac9a790f%2F20241205%2Firan%2Fs3%2Faws4_request&X-Amz-Date=20241205T144349Z&X-Amz-SignedHeaders=host&X-Amz-Expires=1800&X-Amz-Signature=515382d309fbf1a6d3f06002b1be660556763d0fa3b6529794666b386257e66c';
        if($model->is_image()){

            echo Html::img($fileUrl,['class'=>'img-responsive']);
        }
        ?>
    </div>
</div>
