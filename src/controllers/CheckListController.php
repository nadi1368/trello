<?php

namespace hesabro\trello\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AjaxFilter;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectTask;
use hesabro\trello\models\CheckList;
use hesabro\trello\models\CheckListItem;

/**
 * TaskLabelController implements the CRUD actions for TaskLabel model.
 */
class CheckListController extends Controller
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
                    'update', 'delete', 'add', 'update-item', 'delete-item', 'done'
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /*
     * ویرای یا ثبت
     */
    public function actionUpdate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$task->getCheckLists()->active()->one();
        $title=$_POST['title'];
        if($check_list===NULL)
        {
            $check_list=new CheckList();
        }

        $check_list->title_ch=$title;
        $check_list->task_id=$task->id;

        if($check_list->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['index']=$this->renderPartial('_index', [
                'model' => $task,
                'check_list'=>$check_list
            ]);

        }


        return $this->asJson($response);
    }

    /*
    // حذف
     */
    public function actionDelete($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$task->getCheckLists()->active()->one();



        if($check_list->softDelete())
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['index']=$this->renderPartial('_index', [
                'model' => $task,
                'check_list'=>false
            ]);

        }


        return $this->asJson($response);
    }

    /*
    * اضافه کردن ایتم به چک لیست
     */
    public function actionAdd($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $check_list_id=$_POST['pk'];
        $title=$_POST['value'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$this->findModel($check_list_id);

        $model = new CheckListItem();
        $model->title_item=$title;
        $model->check_list_id=$check_list->id;

        if($model->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['list_item']=$this->renderPartial('_list', [
                'model' => $task,
                'check_list'=>$check_list
            ]);

        }

        return $this->asJson($response);
    }

    /*
     * ویرایش عنوان ایتم
     */
    public function actionUpdateItem($id, $check_list_id, $item_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$this->findModel($check_list_id);
        $item=$this->findModelItem($item_id, $check_list_id);
        $item->title_item=$_POST['value'];


        if($item->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['list_item']=$this->renderPartial('_list', [
                'model' => $task,
                'check_list'=>$check_list
            ]);

        }

        return $this->asJson($response);
    }

    /*
     * حذف ایتم
     */
    public function actionDeleteItem($id, $check_list_id, $item_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$this->findModel($check_list_id);
        $item=$this->findModelItem($item_id, $check_list_id);



        if($item->delete())
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['list_item']=$this->renderPartial('_list', [
                'model' => $task,
                'check_list'=>$check_list
            ]);

        }

        return $this->asJson($response);
    }
    /*
     * انجام شده یا خارج کردن از حالت انجام شده یک ایتم
     */
    public function actionDone($id, $check_list_id, $item_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $check_list=$this->findModel($check_list_id);
        $item=$this->findModelItem($item_id, $check_list_id);

        if($item->status==CheckListItem::STATUS_DONE)
        {
            $item->status=CheckListItem::STATUS_NEW;
        }else
        {
            $item->status=CheckListItem::STATUS_DONE;
        }

        if($item->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $task,
            ]);
            $response['list_item']=$this->renderPartial('_list', [
                'model' => $task,
                'check_list'=>$check_list
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
        if (($model = CheckList::findOne($id)) !== null && $model->status==CheckList::STATUS_ACTIVE) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelItem($id, $check_list_id)
    {
        if (($model = CheckListItem::findOne($id)) !== null && $model->check_list_id==$check_list_id) {
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
