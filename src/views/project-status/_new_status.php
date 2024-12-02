<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="list list-created"  id="<?= 'list-'.$status->id; ?>" data-id="<?= $status->id ?>" data-ajax-url="<?= Url::to(['project-status/move', 'id'=>$status->id]) ?>">
    <header>
        <a class="list-title" data-type="text" data-pk="<?= $status->id; ?>" data-url="<?= Url::to(['project-status/update', 'id'=>$status->id]); ?>"   data-placement="right" data-title="<?= Yii::t("app","Update Title") ?>"><?= Html::encode($status->title_status); ?></a>
        <?= $this->render('/project/_menu_header_list',['status' => $status]); ?>
    </header>
    <ul class="connectedSortable" id="<?= 'tasks-ul-'.$status->id; ?>" data-status="<?= $status->id; ?>">
    </ul>
    <footer>
        <a href="#" class="add-new-list" data-type="text" data-pk="<?= $status->id ?>" data-url="<?= Url::to(['task/create']); ?>" data-placement="right" data-title="<?= Yii::t("app","Enter New Task Title") ?>"></a>
    </footer>
</div>