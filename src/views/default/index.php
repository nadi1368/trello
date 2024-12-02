<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title=Yii::t("app","Boards");
?>
<?= $this->render('_board', [
    'projects'=>$projects
]) ?>

<?= $this->render('_team', [
    'teams'=>$teams,
]) ?>
