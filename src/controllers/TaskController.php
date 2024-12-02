<?php

namespace hesabro\trello\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AjaxFilter;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectStatus;
use hesabro\trello\models\ProjectTask;
use hesabro\trello\models\TaskLogs;
use hesabro\trello\models\TaskWatches;
use hesabro\trello\models\TaskAssignment;
use hesabro\trello\models\Label;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\models\Comments;
use hesabro\trello\models\Notifications;
use common\components\jdf\Jdf;
use backend\models\User;
use Exception;

/**
 * TaskController implements the CRUD actions for ProjectTask model.
 */
class TaskController extends Controller
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
                    'view', 'create', 'update', 'move', 'receive', 'change-status', 'watches', 'search-member', 'member', 'due-date-update', 'due-date-complate', 'due-date-delete'
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
     * مشاهده جزئیات تسک در مدال
     */
    public function actionView($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model = $this->findModel($id);
        $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $members=User::find()->limit(5)->all();// لیست ممبر های فعال برای انتخاب در پاپ آور
        $select_members=TaskAssignment::find()->active()->findByTask($id)->all();// لیست ممبر های انتخابی برای این تسک

        $labels=Label::find()->limit(5)->active()->all();// لیست لیبل های فعال برای انتخاب در پاپ آور
        $select_labels=TaskLabel::find()->active()->findByTask($id)->all();// لیست لیبل های انتخابی برای این تسک

        $comments=Comments::find()->active()->findByTask($model->id)->all();

        // مشاهده شده همه ناتیفیکیشن ها
        Notifications::MarkAsView($model->id, Yii::$app->user->id);

        $response['ajax_div']='#task_'.$id;
        $response['task_list_id']='#tasks-ul-'.$model->list_id;
        $response['task_view']=$this->renderPartial('_new', [
            'model' => $model,
        ]);

        $data=$this->renderPartial('_view', [
                'model' => $model,
                'members'=>$members,
                'labels'=>$labels,
                'select_members'=>$select_members,
                'select_labels'=>$select_labels,
                'comments'=>$comments,
            ]);

        $response['success'] = true;
        $response['data'] = $data;



        return $this->asJson($response);

    }

    /**
     * Creates a new ProjectTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $list_id=$_POST['pk'];
        $list=$this->findModelList($list_id);
        $this->findModelProject($list->project_id);// بررسی دسترسی کاربر به این پروژه

        $model = new ProjectTask();
        $model->title_task=$_POST['value'];
        $model->list_id=$list_id;
        $transaction = \Yii::$app->db->beginTransaction();
            try {
            if($flag=$model->save())
            {
                $flag=TaskLogs::Create($model->id, NULL, $list_id,TaskLogs::STATUS_CREATE);

            }

            if ($flag) {

                $data=$this->renderPartial('_new', [
                    'model' => $model,
                ]);
                $response['ajax_div']='#tasks-ul-'.$list_id;
                $response['success'] = true;
                $response['data'] = $data;
                $transaction->commit();
            }else
            {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage() . $e->getTraceAsString(),  __METHOD__ . ':' . __LINE__);
            $transaction->rollBack();
        }


        return $this->asJson($response);
    }

    /**
     * Updates an existing ProjectTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $type)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model = $this->findModel($id);


        $list_id=$_POST['pk'];
        $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه


        if($type=="title")
        {
            $model->title_task=$_POST['value'];
        }else
        {
            $model->desc_task=$_POST['value'];
        }

        if($flag=$model->save())
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
        }



        return $this->asJson($response);
    }

    /*
     * تغیر در ترتیب نمایش
     * در همان لیست
     */
    public function actionMove($id)
    {
        $model=$this->findModel($id);
        $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $new_pos=$_POST['new_position']+1;

        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        if($model->move($new_pos))
        {
            $response['success'] = true;
        }


        return $this->asJson($response);
    }

    /*
     * انتقال تسک به لیست دیگر
     */
    public function actionReceive($id)
        {
            $model=$this->findModel($id);
            $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
            $new_pos=$_POST['new_position']+1;
            $new_status=$_POST['new_status'];

            $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

            if($model->changeStatus($new_pos,$new_status))
            {
                $response['success'] = true;
            }


            return $this->asJson($response);
        }
        
        /*
         * تغیر وضعیت
         * آرچیو و رستور
         */
        public function actionChangeStatus($id)
        {
            $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
            $model=$this->findModel($id);
            $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه

            if($model->status==ProjectTask::STATUS_DELETED)
            {
                $model->status=ProjectTask::STATUS_ACTIVE;
                $response['data'] = '<i class="fa fa-trash-o"></i>&nbsp; '.Yii::t("app","Archive");
            }else
            {
                $model->status=ProjectTask::STATUS_DELETED;
                $response['data'] = '<i class="fa fa-mail-reply"></i>&nbsp; '.Yii::t("app","Restore");
            }

            if($model->save())
            {

                $response['success'] = true;
                $response['ajax_div']='#task_'.$id;
                $response['task_list_id']='#tasks-ul-'.$model->list_id;
                $response['task_view']=$this->renderPartial('_new', [
                    'model' => $model,
                ]);
            }


            return $this->asJson($response);

        }

    /*
     ثبت به عنوان واچز و خارج کردن از حالت واچز
     */
    public function actionWatches($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($id);
        $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $watch=TaskWatches::find()->findByTask($model->id)->findByCreator(Yii::$app->user->id)->One();
        if($watch)
        {
            if($watch->delete())
            {
                $response['success'] = true;
                $response['data'] = '<i class="fa fa-eye"></i>&nbsp; '.Yii::t("app","Watch");
                $response['ajax_div']='#task_'.$id;
                $response['task_list_id']='#tasks-ul-'.$model->list_id;
                $response['task_view']=$this->renderPartial('_new', [
                    'model' => $model,
                ]);
            }
        }else
        {
            $watch=new TaskWatches();
            $watch->task_id=$model->id;
            $watch->old_status=$model->list_id;
            if($watch->save())
            {
                $response['success'] = true;
                $response['data'] = '<i class="fa fa-eye-slash"></i>&nbsp; '.Yii::t("app","Watch");
                $response['ajax_div']='#task_'.$id;
                $response['task_list_id']='#tasks-ul-'.$model->list_id;
                $response['task_view']=$this->renderPartial('_new', [
                    'model' => $model,
                ]);

            }

        }

        return $this->asJson($response);
    }
    
    /*
     * جستجوی ممبر
     */
    public function actionSearchMember($id)
    {
            $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

            $model=$this->findModel($id);
            $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
            $search_string=$_POST['search_string'];

            $members=User::find()
                ->limit(5)
                ->andFilterWhere([
                    'or',
                    ['like', 'username', $search_string],
                    ['like', 'first_name', $search_string],
                    ['like', 'last_name', $search_string],
                ])
                ->all();

            $data=$this->renderPartial('_member', [
                'members'=>$members,
                'model'=>$model,
            ]);

            $response['success'] = true;
            $response['data'] = $data;
            return $this->asJson($response);
    }

    /*
     * اضافه و حذف ممبر به تسک
     */
    public function actionMember($id, $user_id)
    {
            $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

            $model=$this->findModel($id);
            $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
            $member=$this->findModelMember($user_id);
            $task_assignment=TaskAssignment::find()->findByTask($id)->findByUser($user_id)->one();


            if($task_assignment)
            {
                if($task_assignment->status==TaskAssignment::STATUS_ACTIVE)
                {
                    if($task_assignment->softDelete())
                    {
                        // در صورت فعال بود حذف منطقی
                        $response['success'] = true;
                        $response['ajax_div']='#task_'.$id;
                        $response['task_list_id']='#tasks-ul-'.$model->list_id;
                        $response['role'] = 'delete';
                        $response['task_view']=$this->renderPartial('_new', [
                            'model' => $model,
                        ]);
                    }
                }else
                {
                    if($task_assignment->restore())
                    {
                        //در صورت حذف منطقی برگشت
                        $response['success'] = true;
                        $response['ajax_div']='#task_'.$id;
                        $response['task_list_id']='#tasks-ul-'.$model->list_id;
                        $response['role'] = 'add';
                        $response['task_view']=$this->renderPartial('_new', [
                            'model' => $model,
                        ]);
                    }
                }
            }else
            {
                // در صورت موجود نبودن ایجاد می شود
                $task_assignment=new TaskAssignment();
                $task_assignment->project_id=$model->list->project_id;
                $task_assignment->task_id=$model->id;
                $task_assignment->user_id=$user_id;

                if($task_assignment->save())
                {
                    $response['success'] = true;
                    $response['ajax_div']='#task_'.$id;
                    $response['add'] = 'add';
                    $response['task_list_id']='#tasks-ul-'.$model->list_id;
                    $response['task_view']=$this->renderPartial('_new', [
                        'model' => $model,
                    ]);
                }
            }

            $select_members=TaskAssignment::find()->active()->findByTask($id)->all();
            $response['member_list']=$this->renderPartial('_member_list', [
                'select_members' => $select_members,
            ]);
            return $this->asJson($response);
    }

    /*
     * دو دیت
     */
    public function actionDueDateUpdate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModel($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $date=$_POST["date"];
        $time=$_POST["time"];

        $task->end=strtotime(Jdf::Convert_jalali_to_gregorian($date).' '.$time);

        if($task->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('_new', [
                'model' => $task,
            ]);
            $response['due_date_list']=$this->renderPartial('_due_date_list', [
                'model' => $task,
            ]);

        }


        return $this->asJson($response);
    }

    /*
     // کامل شدن و خارج کردن از حالت کامل شده برای تسک
     */
    public function actionDueDateComplate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModel($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه


        if($task->start)
        {
            $task->start=0;// خارج کردن تسک از حالت کامل شده
        }else
        {
            $task->start=time();// تسک کامل شده
        }

        if($task->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('_new', [
                'model' => $task,
            ]);
            $response['due_date_list']=$this->renderPartial('_due_date_list', [
                'model' => $task,
            ]);

        }


        return $this->asJson($response);
    }

    /*
    // حذف دو دیت
     */
    public function actionDueDateDelete($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModel($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه

        $task->end=0;
        $task->start=0;// خارج کردن تسک از حالت کامل شده


        if($task->save(false))
        {
            $response['success'] = true;
            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['task_view']=$this->renderPartial('_new', [
                'model' => $task,
            ]);
            $response['due_date_list']=$this->renderPartial('_due_date_list', [
                'model' => $task,
            ]);

        }


        return $this->asJson($response);
    }
    /**
     * Deletes an existing ProjectTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the ProjectTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ProjectTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelList($id)
    {
        if (($model = ProjectStatus::findOne($id)) !== null ) {
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

    protected function findModelMember($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
