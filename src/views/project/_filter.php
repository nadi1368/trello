<?php
use hesabro\trello\models\ProjectStatus;
use hesabro\trello\Module;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\bootstrap\Html;
?>

<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title" id="exampleModalLongTitle"><?= Module::t('module', 'Filter') ?></h5>
            </div>
            
            <?php $form = ActiveForm::Begin() ?>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'creator_id')->widget(Select2::class, [
                                // 'options' => ['placeholder' => Module::t('module', 'Filter')],
                                'data' => [1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => true
                                ], 
                            ]);?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <?= Html::submitButton(Module::t('module', 'Filter'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Module::t('module', 'Reset'), ['class' => 'btn btn-dark']) ?>
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?= Module::t('module', 'Close') ?></button>
                </div>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
