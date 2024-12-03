<?php
use yii\helpers\Html;
use yii\helpers\Url;
use hesabro\trello\models\TaskWatches;
use hesabro\trello\Module;

$watch = TaskWatches::find()->findByTask($model->id)->findByCreator(Yii::$app->user->id)->One();
$check_list = $model->getCheckLists()->active()->one();
?>

<div class="modal fadeIn bs-example-modal-lg" tabindex="-1" role="dialog" id="dialog" aria-labelledby="task-title">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header rtl">
                <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <a id="task-title" data-type="text" data-pk="<?= $model->id; ?>"
                       data-url="<?= Url::to(['task/update', 'id' => $model->id, 'type' => 'title']); ?>"
                       data-placement="right"
                       data-title="<?= Module::t("module", "Update Title") ?>"><?= Html::encode($model->title_task); ?></a>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2" style="position: relative;">
                        <div class="window-module u-clearfix">
                            <div class="u-clearfix">
                                <a class="button button-default js-change-card-members btn-popover" data-title="Members"
                                   onclick="return showPopOver(this);" data-pop-id="#pop-menu-memeber">
                                    <i class="fa fa-users"></i>&nbsp;<?= Module::t("module", "Members") ?>
                                </a>

                                <a class="button button-default js-edit-labels btn-popover" href="#"
                                   onclick="return showPopOver(this);" data-pop-id="#pop-menu-label">
                                    <i class="fa fa-tags"></i>&nbsp;<?= Module::t("module", "Labels") ?>
                                </a>
                                <a class="button button-default js-add-checklist-menu btn-popover" href="#"
                                   onclick="return showPopOver(this);" data-pop-id="#pop-menu-check-list">
                                    <i class="fa fa-check-square-o"></i>&nbsp;<?= Module::t("module", "Check List") ?>
                                </a>
                                <a class="button button-default js-add-due-date btn-popover" id="popupReturn" href="#"
                                   onclick="return showPopOver(this);" data-pop-id="#pop-menu-dou-date">
                                    <i class="fa fa-clock-o"></i>&nbsp;<?= Module::t("module", "Due Date") ?>
                                </a>
                                <a class="button button-default js-attach btn-popover" href="#"
                                   onclick="return showPopOver(this);" data-pop-id="#pop-menu-attach">
                                    <i class="fa fa-paperclip"></i>&nbsp;<?= Module::t("module", "Attachment") ?>
                                </a>
                                <a class="button <?= $watch ? 'button-success' : 'button-default' ?>  js-attach"
                                   id="btn-watch-task" href="#" data-task-id="<?= '#task_' . $model->id ?>"
                                   data-ajax-url="<?= Url::to(['task/watches', 'id' => $model->id]) ?>"
                                   data-role="<?= $watch ? 'restore' : 'watch' ?>" onclick="return watchesTask(this);">
                                    <i class="<?= $watch ? 'fa fa-eye-slash' : 'fa fa-eye' ?>"></i>&nbsp; <?= Module::t("module", "Watch") ?>
                                </a>
                                <a class="button button-danger js-attach" id="btn-archive-task" href="#"
                                   data-task-id="<?= '#task_' . $model->id ?>"
                                   data-ajax-url="<?= Url::to(['task/change-status', 'id' => $model->id]) ?>"
                                   data-role="archive" onclick="return archiveTask(this);">
                                    <i class="fa fa-trash-o"></i>&nbsp; <?= Module::t("module", "Archive") ?>
                                </a>
                            </div>
                        </div>
                        <?= $this->render('_pop_over_member', ['members' => $members, 'model' => $model]); ?>
                        <?= $this->render('_pop_over_label', ['labels' => $labels, 'model' => $model]); ?>
                        <?= $this->render('_pop_over_check_list', ['model' => $model, 'check_list' => $check_list]); ?>
                        <?= $this->render('_pop_over_dou_date', ['model' => $model]); ?>
                        <?= $this->render('_pop_over_attach', ['model' => $model]); ?>
                    </div>
                    <div class="col-md-10 task-view-body">
                        <div id="list-due-date">
                            <?= $this->render('_due_date_list', ['model' => $model]); ?>
                        </div>

                        <div id="list-label">
                            <?= $this->render('_label_list', ['select_labels' => $select_labels, 'model' => $model]); ?>
                        </div>

                        <div id="list-member">
                            <?= $this->render('_member_list', ['select_members' => $select_members, 'model' => $model]); ?>
                        </div>

                        <h5><i class="fa fa-list"></i> <?= Module::t("module", "Description") ?></h5>
                        <p><a id="task-desc" data-type="textarea" data-rows="3" data-pk="<?= $model->id; ?>"
                              data-url="<?= Url::to(['task/update', 'id' => $model->id, 'type' => 'desc']); ?>"
                              data-placement="right"
                              data-title="<?= Module::t("module", "Update Description") ?>"><?= Html::encode($model->desc_task); ?></a>
                        </p>


                        <div id="list-cheklist">
                            <?= $this->render('/check-list/_index', ['model' => $model, 'check_list' => $check_list]); ?>
                        </div>

                        <div id="list-attach">
                            <?= $this->render('/attach/_list', ['model' => $model]); ?>
                        </div>

                        <div id="list-comment">
                            <?= $this->render('/comments/_list', ['comments' => $comments, 'model' => $model]); ?>
                        </div>

                        <div>
                            <?= $this->render('_activity', ['model' => $model]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


