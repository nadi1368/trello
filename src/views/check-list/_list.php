<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\CheckListItem;
$status_done=CheckListItem::STATUS_DONE;
?>
<?php if($check_list): ?>
    <h5><i class="fa fa-check-square"></i> <?= Html::encode($check_list->title_ch); ?></h5>
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<?= $check_list->getPercent().'%'; ?>">
            <?= $check_list->getPercent().'%'; ?>
        </div>
    </div>
    <div class="list-group without-border rtl">
        <?php foreach ($check_list->getCheckListItems()->all() as $item): ?>
                <div class="check-list-items">
                    <a href="#" class="checked"  data-id="<?= $item->id; ?>"  data-ajax-url="<?= Url::to(['check-list/done', 'id'=>$model->id, 'check_list_id'=>$check_list->id ,'item_id'=>$item->id]) ?>" onclick="return DoneCheckListItem(this);">
                        <i class="<?= $item->status==$status_done ? 'fa fa-check-square-o' : 'fa fa-square-o' ?>"></i>
                    </a>
                    <a href="#" class="list-group-item check-list-item-update <?= $item->status==$status_done ? 'done' : '' ?>" data-type="text" data-pk="<?= $check_list->id; ?>" data-url="<?= Url::to(['check-list/update-item', 'id'=>$model->id, 'check_list_id'=>$check_list->id ,'item_id'=>$item->id]); ?>"   data-placement="right" >
                        <?= $item->title_item; ?>
                    </a>
                    <a href="#" class="delete-item"  data-id="<?= $item->id; ?>"  data-ajax-url="<?= Url::to(['check-list/delete-item', 'id'=>$model->id, 'check_list_id'=>$check_list->id ,'item_id'=>$item->id]) ?>" onclick="return DeleteCheckListItem(this);">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
