<?php

namespace hesabro\trello\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\AjaxFilter;
use yii\web\NotFoundHttpException;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectTask;
use hesabro\trello\models\Comments;

/**
 * TaskLabelController implements the CRUD actions for TaskLabel model.
 */
class CommentsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => AjaxFilter::class,
                'only' => [
                    'create'
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    /**
     * Creates a new TaskLabel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $title=$_POST["title"];
        $model = new Comments();
        $model->task_id=$task->id;
        $model->title_comment=$title;


            if($model->save(false))
            {
                $response['success'] = true;

                $comments=Comments::find()->active()->findByTask($task->id)->all();
                $response['comment_list']=$this->renderPartial('_list', [
                    'model' => $task,
                    'comments'=>$comments,
                ]);

                $response['ajax_div']='#task_'.$id;
                $response['task_list_id']='#tasks-ul-'.$task->list_id;
                $response['task_view']=$this->renderPartial('/task/_new', [
                    'model' => $task,
                ]);

            }


        return $this->asJson($response);
    }



    /**
     * Finds the TaskLabel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TaskLabel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel($id)
    {
        if (($model = Comments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelTask($id)
    {
        if (($model = ProjectTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelProject($id)
    {
        if (($model = Project::findOne($id)) !== null && $model->access(Yii::$app->user->id)) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


}
