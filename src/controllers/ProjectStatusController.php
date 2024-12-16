<?php

namespace hesabro\trello\controllers;

use Yii;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectStatus;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AjaxFilter;

/**
 * ProjectStatusController implements the CRUD actions for ProjectStatus model.
 */
class ProjectStatusController extends Controller
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
                    'index', 'view', 'create', 'update', 'delete', 'restore', 'move'
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
     * Creates a new ProjectStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $p_id=$_POST['pk'];
        $this->findModelProject($p_id);// بررسی دسترسی کاربر به این پروژه
        $model = new ProjectStatus();
        $model->title_status=$_POST['value'];
        $model->project_id=$p_id;
        if($model->save())
        {
            $data=$this->renderPartial('_new_status', [
                'status' => $model,
            ]);

            $response['success'] = true;
            $response['data'] = $data;
        }


        return $this->asJson($response);
    }


    /**
     * ویرایش عنوان
     */
    public function actionUpdate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($id);
        $this->findModelProject($model->project_id);// بررسی دسترسی کاربر به این پروژه

        $model->title_status=$_POST['value'];
        if($model->save())
        {
            $response['success'] = true;
            $response['list_id'] = '#list-'.$id;
        }


        return $this->asJson($response);
    }


    /**
     * Deletes an existing ProjectStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $this->findModelProject($model->project_id);// بررسی دسترسی کاربر به این پروژه


        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        if($model->softDelete())
        {
            $project=$this->findModelProject($model->project_id);
            $archive_statuses=$project->getProjectStatuses()->deActive()->orderBy('s_order')->all(); // لیست های آرشیو شده

            $data=$this->renderPartial('/project/_menu_archive_list', ['archive_statuses'=>$archive_statuses]);
            $response['success'] = true;
            $response['data'] = $data;
        }


        return $this->asJson($response);
    }


    public function actionRestore($id)
    {
        $model=$this->findModel($id);
        $this->findModelProject($model->project_id);// بررسی دسترسی کاربر به این پروژه


        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        if($model->restore())
        {
            $next_status=ProjectStatus::find()
                ->findByProject($model->project_id)
                ->andWhere('s_order>:SOrder',[':SOrder'=>$model->s_order])
                ->one();
            if($next_status)
            {
                $next_list_id='#list-'.$next_status->id;
            }else
            {
                $next_list_id='#list-new';
            }
            $data=$this->renderPartial('_list', ['status'=> $model]);
            $response['success'] = true;
            $response['data'] = $data;
            $response['next_list_id'] = $next_list_id;
        }


        return $this->asJson($response);
    }



    /*
     * انتقال لیست
     * تغیر در ترتیب نمایش
     */
    public function actionMove($id)
    {
        $model=$this->findModel($id);
        $this->findModelProject($model->project_id);// بررسی دسترسی کاربر به این پروژه
        $new_pos=$_POST['new_position']+1;

        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        if($model->move($new_pos))
        {
            $response['success'] = true;
        }


        return $this->asJson($response);
    }

    /**
     * Finds the ProjectStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectStatus::findOne($id)) !== null) {
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
