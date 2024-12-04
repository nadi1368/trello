<?php

namespace hesabro\trello\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectTask;
use hesabro\trello\models\Attachments;
use hesabro\trello\models\AttachUploadForm;
use yii\web\UploadedFile;

/**
 * TaskLabelController implements the CRUD actions for TaskLabel model.
 */
class AttachController extends Controller
{

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
        $response = ['success' => false, 'data' => '', 'msg' => 'خطا در ثبت اطلاعات.'];
        $task = $this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه

        $model = new Attachments(['scenario' => Attachments::SCENARIO_CREATE,  'task_id' => $task->id]);

        if ($model->save()) {
            $response['success'] = true;

            $response['ajax_div'] = '#task_' . $id;
            $response['task_list_id'] = '#tasks-ul-' . $task->list_id;
            $response['task_view'] = $this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['attach_list'] = $this->renderPartial('_list', [
                'model' => $task,
            ]);
        } else {
            $response['msg'] = Html::errorSummary($model, ['header' => '']);
        }

        return $this->asJson($response);
    }


    public function actionDelete($id)
    {
        $response = ['success' => false, 'data' => '', 'msg' => 'خطا در ثبت اطلاعات.'];
        $model = $this->findModel($id);
        $task = $this->findModelTask($model->task_id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه


        if ($model->softDelete()) {
            $response['success'] = true;

            $response['ajax_div'] = '#task_' . $task->id;
            $response['task_list_id'] = '#tasks-ul-' . $task->list_id;
            $response['task_view'] = $this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['attach_list'] = $this->renderPartial('_list', [
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
        if (($model = Attachments::findOne($id)) !== null) {
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
