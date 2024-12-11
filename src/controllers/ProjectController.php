<?php

namespace hesabro\trello\controllers;

use yii\data\ActiveDataProvider;
use hesabro\trello\models\TaskLogs;
use Yii;
use backend\models\User;
use hesabro\trello\models\Project;
use hesabro\trello\models\ProjectUser;
use hesabro\trello\models\Team;
use hesabro\trello\models\ProjectTeams;
use hesabro\trello\models\TeamUsers;
use Exception;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AjaxFilter;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                    'create', 'update'
                ],
            ],
        ];
    }



    public function beforeAction($action)
    {
        if($action->id!=='index')
        {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex($p_id)
    {
        $project=$this->findModel($p_id);
        $statuses=$project->getProjectStatuses()->active()->orderBy('s_order')->all();// لیست های فعال
        $archive_statuses=$project->getProjectStatuses()->deActive()->orderBy('s_order')->all(); // لیست های آرشیو شده
        return $this->render('index', [
            'project' => $project,
            'statuses'=>$statuses,
            'archive_statuses'=>$archive_statuses,
        ]);
    }



    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model = new Project();

        $model->project_name=$_POST['title'];
        $model->public_or_private=$_POST['public'];
        $model->color=$_POST['color'];
        $model->user_id=Yii::$app->user->id;

        $team_id=$_POST['team'];
        $is_admin_in_team=TeamUsers::access($team_id, Yii::$app->user->id, true);// در صورتی که ادمین این تیم باشد
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($flag=$model->save()) {

                $flag=ProjectUser::saveAfterCreateProject($model->id, $model->user_id);// ذخیره مشخصات این کاربر به عنوان کاربر این پروزه با سطح دسترسی مدیر
                if($flag && $is_admin_in_team)
                {
                    $project_team=new ProjectTeams();
                    $project_team->project_id=$model->id;
                    $project_team->team_id=$team_id;
                    $flag=$project_team->save();
                }
            }

            if ($flag) {
                $transaction->commit();
                $response['success'] = true;
                $response['url_new_board'] = Url::to(['project/index','p_id'=>$model->id]);
            }else
            {
                $transaction->rollBack();
            }
        }catch (Exception $e) {
            Yii::error($e->getMessage() . $e->getTraceAsString(),  __METHOD__ . ':' . __LINE__);
            $transaction->rollBack();
        }

        return $this->asJson($response);
    }

    /**
     * ویرایش
     */

    public function actionUpdate($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model = $this->findModel($id);

        $model->project_name=$_POST['title'];
        $model->public_or_private=$_POST['public'];
        $old_color=$model->color;
        $model->color=$_POST['color'];
        if ($model->isAdmin(Yii::$app->user->id) && $model->save()) {
            $response['success'] = true;
            $response['new_color'] = $model->color;
            $response['old_color'] = $old_color;
            $response['project_name'] = $model->project_name;
            $response['project_id'] = '#main-board-'.$model->id;
        }

        return $this->asJson($response);
    }


    /**
     * جستجوی کاربر
     */
    public function actionSearchMember($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);
        $search_string=$_POST['search_string'];

        if(!empty($search_string))
        {

            $sub_query_project_user=ProjectUser::find()->select('user_id')->active()->findByProject($model->id);
            $members=User::find()
                ->limit(5)
                ->andWhere(['not in','id',$sub_query_project_user])
                ->andFilterWhere([
                    'or',
                    ['like', 'username', $search_string],
                    ['like', 'first_name', $search_string],
                    ['like', 'last_name', $search_string],
                ])
                ->all();
        }else
        {
            $members=false;
        }

        $data=$this->renderPartial('_member', [
            'members'=>$members,
            'model'=>$model,
        ]);

        $response['success'] = true;
        $response['data'] = $data;
        return $this->asJson($response);
    }
    
    /**
     *  اضافه کردن ممبر به پروژه
     */
    public function actionAddMember($id, $user_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);
        $project_user=new ProjectUser();
        $project_user->project_id=$model->id;
        $project_user->user_id=$user_id;
        $project_user->role=ProjectUser::ROLE_USER;
        if($project_user->save())
        {
            $data=$this->renderPartial('_pop_over_project_member', [
                'project'=>$model,
            ]);

            $response['success'] = true;
            $response['project_name'] = $model->project_name;
            $response['data'] = $data;
            $response['project_id'] = '#main-board-'.$model->id;
        }
        return $this->asJson($response);
    }

    /**
     * @param $id
     * @param $project_user
     * @throws
     * حذف کاربر از این پروزه
     */
    public function actionRemoveMember($id, $project_user)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);

        if($model->isAdmin(Yii::$app->user->id))
        {
            //فقط کاربران مدیر پروژه اجازه حذف کاربر را دارند
            // کاربری که میخواهیم حذف شود
            $project_user=ProjectUser::find()
                ->findByProject($model->id)// به این پروژه دسترسی داشته باشد
                ->active()
                ->andWhere(
                    'id=:Id AND is_creator=:No',
                    [
                        ':Id'=>$project_user,
                        ':No'=>ProjectUser::NO,// و ایجاد ککنده پروزه نباشد
                    ]
                )
                ->one();
            if($project_user && $project_user->softDelete())
            {
                $data=$this->renderPartial('_pop_over_project_member', [
                    'project'=>$model,
                ]);

                $response['success'] = true;
                $response['data'] = $data;
                $response['project_id'] = '#main-board-'.$model->id;
            }
        }
        return $this->asJson($response);
    }

    /**
     * @param $id
     * @param $project_user
     * @throws NotFoundHttpException
     * تغغیر سطح دسترسی کاربران پروژه
     */
    public function actionChangeRoleMember($id, $project_user)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);

        if($model->isAdmin(Yii::$app->user->id))
        {
            //فقط کاربران مدیر پروژه اجازه حذف کاربر را دارند
            // کاربری که میخواهیم حذف شود
            $project_user=ProjectUser::find()
                ->findByProject($model->id)// به این پروژه دسترسی داشته باشد
                ->active()
                ->andWhere(
                    'id=:Id AND is_creator=:No',
                    [
                        ':Id'=>$project_user,
                        ':No'=>ProjectUser::NO,// و ایجاد ککنده پروزه نباشد
                    ]
                )
                ->one();
            if($project_user->role==ProjectUser::ROLE_ADMIN)
            {
                $project_user->role=ProjectUser::ROLE_USER;
            }else
            {
                $project_user->role=ProjectUser::ROLE_ADMIN;
            }

            if($project_user && $project_user->save())
            {
                $data=$this->renderPartial('_pop_over_project_member', [
                    'project'=>$model,
                ]);

                $response['success'] = true;
                $response['data'] = $data;
                $response['project_id'] = '#main-board-'.$model->id;
            }
        }
        return $this->asJson($response);
    }

    /**
     * جستجوی کاربر
     */
    public function actionSearchTeam($id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);
        $search_string=$_POST['search_string'];

        if(!empty($search_string))
        {

            $sub_query_project_team=ProjectTeams::find()->select('team_id')->active()->findByProject($model->id);
            $sub_query_user_team=TeamUsers::find()->select('team_id')->active()->findByUser(Yii::$app->user->id);

            $teams=Team::find()
                ->limit(5)
                ->andWhere(['not in','id',$sub_query_project_team])
                ->andWhere(['in','id',$sub_query_user_team])
                ->andFilterWhere([
                    'or',
                    ['like', 'title_team', $search_string],
                ])
                ->all();
        }else
        {
            $teams=false;
        }

        $data=$this->renderPartial('_team', [
            'teams'=>$teams,
            'model'=>$model,
        ]);

        $response['success'] = true;
        $response['data'] = $data;
        return $this->asJson($response);
    }

    /**
     *  اضافه کردن ممبر به پروژه
     */
    public function actionAddTeam($id, $team_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);
        $project_team=new ProjectTeams();
        $project_team->project_id=$model->id;
        $project_team->team_id=$team_id;
        if($project_team->save())
        {
            $data=$this->renderPartial('_pop_over_project_team', [
                'project'=>$model,
            ]);

            $response['success'] = true;
            $response['data'] = $data;
            $response['project_id'] = '#main-board-'.$model->id;
        }
        return $this->asJson($response);
    }

    /**
     * @param $id
     * @param $project_user
     * @throws
     * حذف کاربر از این پروزه
     */
    public function actionRemoveTeam($id, $project_team)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];

        $model=$this->findModel($id);

        if($model->isAdmin(Yii::$app->user->id))
        {
            //فقط کاربران مدیر پروژه اجازه حذف تیم را دارند
            // تیمی که میخواهیم حذف شود
            $project_user=ProjectTeams::find()
                ->findByProject($model->id)// به این پروژه دسترسی داشته باشد
                ->active()
                ->andWhere(
                    'id=:Id',
                    [
                        ':Id'=>$project_team,
                    ]
                )
                ->one();
            if($project_user && $project_user->softDelete())
            {
                $data=$this->renderPartial('_pop_over_project_team', [
                    'project'=>$model,
                ]);

                $response['success'] = true;
                $response['data'] = $data;
                $response['project_id'] = '#main-board-'.$model->id;
            }
        }
        return $this->asJson($response);
    }

    public function actionAjaxGetActivities() 
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TaskLogs::find()->orderBy(['created' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->renderAjax('_pop_over_project_activity', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null && $model->access(Yii::$app->user->id)) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
