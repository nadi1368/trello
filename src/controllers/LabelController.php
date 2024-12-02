<?php

namespace hesabro\trello\controllers;

use Yii;
use hesabro\trello\models\TaskLabel;
use hesabro\trello\models\TaskLabelSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectTask;
use hesabro\trello\models\Label;
use Exception;
use yii\filters\AjaxFilter;

/**
 * TaskLabelController implements the CRUD actions for TaskLabel model.
 */
class LabelController extends Controller
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
                    'index', 'view', 'create', 'update', 'delete', 'search' , 'toggle'
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
     * Lists all TaskLabel models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $searchModel = new TaskLabelSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }

    /**
     * Displays a single TaskLabel model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

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
        $title=$_POST['title'];
        $color=$_POST['color'];
        $model = new Label();
        $model->label_name=$title;
        $model->color_code=$color;

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if($flag=$model->save(false))
            {
                $task_label=new TaskLabel();
                $task_label->task_id=$task->id;
                $task_label->label_id=$model->id;
                $flag=$task_label->save(false);
            }
            if ($flag) {

                $labels=Label::find()->limit(5)->active()->all();
                $response['success'] = true;
                $response['data'] =$this->renderPartial('/task/_labels_list_search', [
                    'labels' => $labels,
                    'model'=>$task,
                ]);

                $select_labels=TaskLabel::find()->active()->findByTask($id)->all();
                $response['label_list']=$this->renderPartial('/task/_label_list', [
                    'select_labels'=>$select_labels,
                ]);

                $response['ajax_div']='#task_'.$id;
                $response['task_list_id']='#tasks-ul-'.$task->list_id;
                $response['task_view']=$this->renderPartial('/task/_new', [
                    'model' => $task,
                ]);

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
     * Updates an existing TaskLabel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $label_id=$_POST['label_id'];
        $model=$this->findModel($label_id);
        $title=$_POST['title'];
        $color=$_POST['color'];
        $model->label_name=$title;
        $model->color_code=$color;


        if ($model->save()) {
            $labels=Label::find()->limit(5)->active()->all();
            $response['success'] = true;
            $response['data'] =$this->renderPartial('/task/_labels_list_search', [
                'labels' => $labels,
                'model'=>$task,
            ]);

            $select_labels=TaskLabel::find()->active()->findByTask($id)->all();
            $response['label_list']=$this->renderPartial('/task/_label_list', [
                'select_labels'=>$select_labels,
            ]);

            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
            $response['label_view']=$this->renderPartial('_view', [
                'label' => $model,
            ]);
        }


        return $this->asJson($response);
    }

    /**
     * Deletes an existing TaskLabel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $label_id=$_POST['label_id'];
        $model=$this->findModel($label_id);


        if ($model->softDelete()) {

            $labels=Label::find()->limit(5)->active()->all();
            $response['success'] = true;
            $response['data'] =$this->renderPartial('/task/_labels_list_search', [
                'labels' => $labels,
                'model'=>$task,
            ]);

            $select_labels=TaskLabel::find()->active()->findByTask($id)->all();
            $response['label_list']=$this->renderPartial('/task/_label_list', [
                'select_labels'=>$select_labels,
            ]);

            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$task->list_id;
        }


        return $this->asJson($response);
    }

    /*
     * جستجوی لیبل ها
     * در پاپ آور
     */
    public function actionSearch($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $task=$this->findModelTask($id);
        $this->findModelProject($task->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $search_string=$_POST['search_string'];

        $labels=Label::find()
            ->limit(5)
            ->andFilterWhere([
                'or',
                ['like', 'label_name', $search_string],
            ])
            ->active()
            ->all();

        $response['data']=$this->renderPartial('/task/_labels_list_search', [
            'labels' => $labels,
            'model'=>$task,
        ]);

        $response['success'] = true;
        return $this->asJson($response);
    }
    /*
     * اضافه کردن یا خارج کردن از انتخاب یک لیبل برای تسک
     */
    public function actionToggle($id,$label_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModelTask($id);
        $this->findModelProject($model->list->project_id);// بررسی دسترسی کاربر به این پروژه
        $label=$this->findModel($label_id);

        $task_label=TaskLabel::find()->findByTask($model->id)->findByLabel($label->id)->One();
        if($task_label)
        {
            if($task_label->delete())
            {
                $response['success'] = true;
            }
        }else
        {
            $task_label=new TaskLabel();
            $task_label->task_id=$model->id;
            $task_label->label_id=$label->id;
            if($task_label->save())
            {
                $response['success'] = true;

            }
        }

        if($response['success'])
        {
            $labels=Label::find()->limit(5)->active()->all();
            $response['data']=$this->renderPartial('/task/_labels_list_search', [
                'labels' => $labels,
                'model'=>$model,
            ]);

            $select_labels=TaskLabel::find()->active()->findByTask($id)->all();
            $response['label_list']=$this->renderPartial('/task/_label_list', [
                'select_labels'=>$select_labels,
            ]);

            $response['ajax_div']='#task_'.$id;
            $response['task_list_id']='#tasks-ul-'.$model->list_id;
            $response['task_view']=$this->renderPartial('/task/_new', [
                'model' => $model,
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
    protected function findModelTaskLabel($id)
    {
        if (($model = TaskLabel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    protected function findModel($id)
    {
        if (($model = Label::findOne($id)) !== null) {
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
