<?php

namespace hesabro\trello\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use hesabro\trello\models\ProjectUser;
use hesabro\trello\models\Project;
use hesabro\trello\models\Team;
use hesabro\trello\models\TeamUsers;
use Exception;

/**
 * Default controller for the `trello` module
 */
class DefaultController extends Controller
{
    public $layout="team";

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

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        $user_id=Yii::$app->user->id;

        $sub_query_team_user=TeamUsers::find()
            ->select('project_id')
            ->joinWith([
                'team'=>function($query){
                    return $query->active();
                }
            ])
            ->leftJoin('tbl_project_teams', '`tbl_project_teams`.`team_id` = `tbl_team_users`.`team_id`')
            ->active()
            ->findByUser($user_id);// پروژه هایی که تیم یا تیم های این کاربر روی آن کار می کنند

        $sub_query=ProjectUser::find()->select('project_id')->findByUser($user_id)->active();// پروژه های که این کاربر اجازه دسترسی به آنها را دارد
        $projects=Project::find()
            ->Where(['OR', ['in','id',$sub_query], ['in','id',$sub_query_team_user], ['=','public_or_private',Project::SHOW_PUBLIC]])// یا پروژه هایی که عمومی هستند
            ->active()
            ->all();

        $teams=TeamUsers::find()->findByAdmin()->findByUser($user_id)->active()->all();

        return $this->render('index',[
            'projects'=>$projects,
            'teams'=>$teams,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCreateTeam()
    {
        $response=['success'=>false, 'data'=>'', 'msg'=>'خطا در ثبت اطلاعات.'];
        $model = new Team();

        $model->title_team=$_POST['title'];

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if($flag=$model->save(false))
            {
                $team_user=new TeamUsers();
                $team_user->team_id=$model->id;
                $team_user->user_id=Yii::$app->user->id;
                $team_user->role=TeamUsers::ROLE_ADMIN; // کاربری که تیم را ایجاد می کند به عنوان اولین عضو این تیم نقش مدیر می گیرد.
                $team_user->is_creator=TeamUsers::YES;// به عنوان ادمین اصلی
                $flag=$team_user->save();
            }

            if ($flag) {
                $transaction->commit();
                $response['success'] = true;
                $response['url_new_board'] = Url::to(['teams/index','id'=>$model->id]);
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

}
