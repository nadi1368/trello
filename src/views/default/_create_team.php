<?php
use yii\helpers\Url;
?>
<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t("app","Create Team Board") ?></h4>
            </div>
            <div class="modal-body">
                <div class="row rtl">

                    <div class="col-md-12">
                        <div class="form-input">
                            <input type="tex" id="title-team-input" class="form-control rtl" placeholder="<?= Yii::t("app","Team Title") ?>">
                        </div>
                    </div>

                    <div class="clearfix"></div>


                    <div class="col-md-12">
                        <p class="text-danger fade-out rtl" id="msg-team-input"><?= Yii::t("app","Please Fill Form") ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" data-ajax-url="<?= Url::to(['create-team']) ?>" onclick="return createTeam(this);"><?= Yii::t("app","Create"); ?></button>
            </div>
        </div>
    </div>
</div>