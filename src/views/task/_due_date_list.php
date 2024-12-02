<?php
if($model->end){
    echo '<h5><i class="fa fa-clock-o"></i> '.Yii::t("app","Due Date").'</h5>';
    echo $model->getDueDate('view');
}
?>