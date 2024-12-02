<?php
use yii\helpers\Url;
use yii\helpers\Html;
use hesabro\trello\models\Project;
use hesabro\trello\models\Team;
use hesabro\trello\models\TaskLabel;
$colors=TaskLabel::itemAlias('Color');
?>
<div class="modal fade" id="createBoardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t("app","Create New Board") ?></h4>
            </div>
            <div class="modal-body">
                <div class="row rtl">
                    <div class="col-md-12">
                        <div class="form-input">
                            <input type="tex" id="title-board-input" class="form-control rtl" placeholder="<?= Yii::t("app","Board Title") ?>">
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-6">
                        <div class="form-input">
                            <?= Html::dropDownList('team',0,Team::itemAlias('List'),['class'=>'form-control', 'id'=>"team-board-input", 'prompt'=>'']); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-input">
                            <?= Html::dropDownList('status',0,Project::itemAlias('ShowStatus'),['class'=>'form-control', 'id'=>"public-board-input"]); ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-12">
                        <?php
                            $index=0;
                            $input_value="green";
                            foreach($colors as $title=>$css_class):
                        ?>
                                    <span class="card-label mod-edit-label mod-clickable <?= $css_class; ?>" data-color="<?= $title; ?>" title="<?= $title; ?>">
                                <?php
                                    if($index++==0)
                                    {
                                        echo '<i class="fa fa-check"></i>';
                                        $input_value=$title;
                                    }else
                                    {
                                        echo '<i class="fa"></i>';
                                    }
                                ?>
                                     </span>
                        <?php endforeach; ?>
                        <input type="hidden" id="color-board-input" value="<?= $input_value ?>">
                    </div>
                    <div class="clearfix"></div>
                        <div class="col-md-12">
                            <p class="text-danger fade-out rtl" id="msg-board-input"><?= Yii::t("app","Please Fill Form") ?></p>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" data-ajax-url="<?= Url::to(['project/create']) ?>" onclick="return createBoard(this);"><?= Yii::t("app","Create"); ?></button>

            </div>
        </div>
    </div>
</div>