<?php
use hesabro\trello\Module;

if($model->end){
    echo '<h5><i class="fa fa-clock-o"></i> '.Module::t("module","Due Date").'</h5>';
    echo $model->getDueDate('view');
}
?>