<?php
use yii\helpers\Url;
use hesabro\trello\Module;
use hesabro\helpers\widgets\DateRangePicker\DateRangePicker;
use yii\bootstrap4\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
]); ?>

<div id="pop-menu-dou-date" class="pop-over">
    <div class="pop-over-header js-pop-over-header">
        <span class="pop-over-header-title"><?= Module::t("module","Dou Date") ?></span>
        <a href="#" class="pop-over-header-close-btn" onclick="return closePopOver(this);"><i class="fa fa-times"></i> </a>
    </div>
    <div class="pop-over-content">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'end')->widget(DateRangePicker::class, [
                    'options'     => [
                        'locale'            => [
                            'format' => 'jYYYY/jMM/jDD HH:mm:ss',
                        ],
                        'drops'             => 'down',
                        'opens'             => 'right',
                        'jalaali'           => true,
                        'showDropdowns'     => true,
                        'language'          => 'fa',
                        'singleDatePicker'  => true,
                        'useTimestamp'      => true,
                        'timePicker'        => true,
                        'timePickerSeconds' => true,
                        'timePicker24Hour'  => true
                    ],
                    'htmlOptions' => [
                        'id'           => 'employeerollcallsearch-from-date',
                        'class'        => 'form-control',
                        'autocomplete' => 'off',
                        ]
                    ]); ?>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <input type="tex" id="date-duedate-input" class="form-control rtl" value="<?= Yii::$app->jdate->date("Y/m/d",$model->end ? $model->end : time()); ?>">
        </div>
        <div class="col-md-6">
            <input type="tex" id="time-duedate-input" class="form-control rtl" value="<?= Yii::$app->jdate->date("H:i",$model->end ? $model->end : time()); ?>">
        </div> -->
        <p class="text-danger fade-out rtl" id="msg-duedata-input"><?= Module::t("module","Please Fill Form") ?></p>
        <div class="clearfix"></div>
        <hr/>
        <a class="button button-success button-inline" data-ajax-url="<?= Url::to(['task/due-date-update', 'id' => $model->id]) ?>" onclick="return updateDueDate(this);"><?= Module::t("module","Save") ?></a>
        <a class="button button-danger button-inline pull-right"  data-ajax-url="<?= Url::to(['task/due-date-delete', 'id' => $model->id]) ?>" onclick="return deleteDueDate(this);"><?= Module::t("module","Remove") ?></a>
    </div>
</div>

<?php ActiveForm::end(); ?>