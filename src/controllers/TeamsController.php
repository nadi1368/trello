<?php

namespace hesabro\trello\controllers;

use Yii;
use common\models\Customer;
use hesabro\trello\models\Project;
use hesabro\trello\models\Team;
use hesabro\trello\models\ProjectTeams;
use hesabro\trello\models\TeamUsers;
use backend\models\User;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AjaxFilter;

/**
 * ProjectTeamsController implements the CRUD actions for ProjectTeams model.
 */
class TeamsController extends Controller
{
    public $layout="team";
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
                    'search-user', 'add-user', 'delete-user', 'change-role-user'
                ],
            ],
        ];
    }

    /**
     * Lists all ProjectTeams models.
     * @return mixed
     */
    public function actionIndex($id, $project_id=false)
    {
        $model=$this->findModel($id);

        $users_in_team=$model->getTeamUsers()->select('user_id')->active();
        $customer=Customer::find()->select('user_id');
        $list_user_for_add=User::find()
            ->andWhere(['NOT IN','id',$users_in_team])
            ->andWhere(['NOT IN','id',$customer])
            ->limit(10)
            ->all();


        return $this->render('index', [
            'model' => $model,
            'list_user_for_add'=>$list_user_for_add,
            'project'=>$project_id ? $this->findModelProject($project_id) : false,
        ]);
    }



    /**
     * Creates a new ProjectTeams model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        $project=$this->findModelProject($project_id);
        $model = new Team();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if($flag=$model->save(false))
                {
                    $project_team=new ProjectTeams();
                    $project_team->project_id=$project->id;
                    $project_team->team_id=$model->id;
                    if($flag=$project_team->save())
                    {
                        $team_user=new TeamUsers();
                        $team_user->team_id=$model->id;
                        $team_user->user_id=Yii::$app->user->id;
                        $team_user->role=TeamUsers::ROLE_ADMIN; // کاربری که تیم را ایجاد می کند به عنوان اولین عضو این تیم نقش مدیر می گیرد.
                        $team_user->is_creator=TeamUsers::YES;// به عنوان ادمین اصلی
                        $flag=$team_user->save();
                    }

                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['index', 'id' => $model->id, 'project_id'=>$project->id]);
                }else
                {
                    $transaction->rollBack();
                }

            }catch (Exception $e) {
                Yii::error($e->getMessage() . $e->getTraceAsString(),  __METHOD__ . ':' . __LINE__);
                $transaction->rollBack();
            }


        }


    }

    /**
     * جستجوی یوزر
     */
    public function actionSearchUser($team_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($team_id);
        $users_in_team=$model->getTeamUsers()->select('user_id')->active();
        $customer=Customer::find()->select('user_id');
        $search_string=$_POST['search_string'];

        $list_user_for_add=User::find()
            ->andWhere(['NOT IN','id',$users_in_team])
            ->andWhere(['NOT IN','id',$customer])
            ->andFilterWhere([
                'or',
                ['like', 'username', $search_string],
                ['like', 'first_name', $search_string],
                ['like', 'last_name', $search_string],
            ])
            ->limit(10)
            ->all();


        $response['success'] = true;
        $response['data']=$this->renderPartial('_add_user', [
            'model' => $model,
            'list_user_for_add'=>$list_user_for_add
        ]);

        return $this->asJson($response);
    }

    /**
     * اضافه کردن کاربر به تیم
     */
    public function actionAddUser($team_id, $user_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($team_id);
        $user=$this->findModelUser($user_id);

        $team_user=new TeamUsers();
        $team_user->team_id=$model->id;
        $team_user->user_id=$user->id;
        $team_user->role=TeamUsers::ROLE_USER; // کاربری که تیم را ایجاد می کند به عنوان اولین عضو این تیم نقش مدیر می گیرد.
        $team_user->is_creator=TeamUsers::NO;// به عنوان ادمین اصلی
        if($team_user->save())
        {
            $users_in_team=$model->getTeamUsers()->select('user_id')->active();
            $customer=Customer::find()->select('user_id');

            $list_user_for_add=User::find()
                ->andWhere(['NOT IN','id',$users_in_team])
                ->andWhere(['NOT IN','id',$customer])
                ->limit(10)
                ->all();

            $response['success'] = true;
            $response['search_user']=$this->renderPartial('_add_user', [
                'model' => $model,
                'list_user_for_add'=>$list_user_for_add
            ]);
            $response['list_user']=$this->renderPartial('_list_user', [
                'model' => $model,
            ]);
        }



        return $this->asJson($response);
    }

    /**
     * حذف کردن کاربر از تیم
     */
    public function actionDeleteUser($team_id, $teamUser_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($team_id);
        $team_user=$this->findModelTeamUsers($teamUser_id, $team_id);


        if($team_user->is_creator==TeamUsers::NO && $team_user->softDelete())
        {
            $users_in_team=$model->getTeamUsers()->select('user_id')->active();
            $customer=Customer::find()->select('user_id');

            $list_user_for_add=User::find()
                ->andWhere(['NOT IN','id',$users_in_team])
                ->andWhere(['NOT IN','id',$customer])
                ->limit(10)
                ->all();

            $response['success'] = true;
            $response['search_user']=$this->renderPartial('_add_user', [
                'model' => $model,
                'list_user_for_add'=>$list_user_for_add
            ]);
            $response['list_user']=$this->renderPartial('_list_user', [
                'model' => $model,
            ]);
        }



        return $this->asJson($response);
    }

    /**
     * تغییر رل کاربر
     */
    public function actionChangeRoleUser($team_id, $teamUser_id)
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model=$this->findModel($team_id);
        $team_user=$this->findModelTeamUsers($teamUser_id, $team_id);
        $role=$_POST['role'];

        $team_user->role=$role;
        if(array_key_exists($role,TeamUsers::itemAlias('Role')) && $team_user->is_creator==TeamUsers::NO && $team_user->save())
        {
            $users_in_team=$model->getTeamUsers()->select('user_id')->active();
            $customer=Customer::find()->select('user_id');

            $list_user_for_add=User::find()
                ->andWhere(['NOT IN','id',$users_in_team])
                ->andWhere(['NOT IN','id',$customer])
                ->limit(10)
                ->all();

            $response['success'] = true;
            $response['search_user']=$this->renderPartial('_add_user', [
                'model' => $model,
                'list_user_for_add'=>$list_user_for_add
            ]);
            $response['list_user']=$this->renderPartial('_list_user', [
                'model' => $model,
            ]);
        }



        return $this->asJson($response);
    }

    /**
     * Updates an existing ProjectTeams model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Deletes an existing ProjectTeams model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProjectTeams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectTeams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Team::findOne($id)) !== null && $model->isAdmin(Yii::$app->user->id)) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    protected function findModelProject($id)
    {
        if (($model = Project::findOne($id)) !== null && $model->isAdmin(Yii::$app->user->id)) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    protected function findModelUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            if(Customer::find()->andWhere('user_id=:User',[':User'=>$model->id])->one()=== null)// یوزر جز مشتری ها نباشد
                return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    protected function findModelTeamUsers($id, $team_id)
    {
        if (($model = TeamUsers::findOne($id)) !== null && $team_id==$model->team_id) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
