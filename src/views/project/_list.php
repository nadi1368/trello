<?php
use yii\helpers\Html;
use yii\helpers\Url;
use hesabro\trello\Module;
?>
<?php foreach($statusesdataProvider->getModels() as $index=>$status): ?>
    <div class="list list-created"
         id="<?= 'list-'.$status->id; ?>" 
         data-id="<?= $status->id ?>" 
         data-ajax-url="<?= Url::to(['project-status/move', 'id'=>$status->id]) ?>"
    >
        <header>
            <a class="list-title" 
               data-type="text" 
               data-pk="<?= $status->id; ?>" 
               data-url="<?= Url::to(['project-status/update', 'id' => $status->id]); ?>"   
               data-placement="right" data-title="<?= Module::t("module","Update Title") ?>"
            >
                <?= Html::encode($status->title_status); ?>
            </a>
            <?= $this->render('_menu_header_list',['status' => $status]); ?>
        </header>
        <ul class="connectedSortable" id="<?= 'tasks-ul-'.$status->id; ?>" data-status="<?= $status->id; ?>">
            <?= $this->render('_list_task',['status' => $status]); ?>
        </ul>
        <footer>
            <a href="#" class="add-new-list" data-type="text" data-pk="<?= $status->id ?>" data-url="<?= Url::to(['task/create']); ?>" data-placement="right" data-title="<?= Module::t("module","Enter New Task Title") ?>"></a>
        </footer>
    </div>
<?php endforeach; ?>

<div class="list" id="list-new">
    <div class="list-new">
        <a href="#" id="add-new-list"  data-type="text" data-pk="<?= $project->id; ?>" data-url="<?= Url::to(['project-status/create']); ?>"   data-placement="right" data-title="<?= Module::t("module","Enter New Project Status Title") ?>"></a>
    </div>
</div>