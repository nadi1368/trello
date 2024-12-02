<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php if($check_list): ?>
    <div id="item-checklist">
        <?= $this->render('/check-list/_list',['model'=>$model, 'check_list'=>$check_list]); ?>
    </div>
    <a href="#" id="add-new-check-list-item"  data-type="text" data-pk="<?= $check_list->id; ?>" data-url="<?= Url::to(['check-list/add', 'id'=>$model->id]); ?>"   data-placement="right" data-title="<?= Yii::t("app","Add an item") ?>"></a>
<?php endif; ?>