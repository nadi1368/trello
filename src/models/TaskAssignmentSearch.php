<?php

namespace hesabro\trello\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use hesabro\trello\models\TaskAssignment;

/**
 * TaskAssignmentSearch represents the model behind the search form of `hesabro\trello\models\TaskAssignment`.
 */
class TaskAssignmentSearch extends TaskAssignment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'update_id', 'task_id', 'user_id', 'project_id', 'parent_id', 'status', 'created', 'changed'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TaskAssignment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'creator_id' => $this->creator_id,
            'update_id' => $this->update_id,
            'task_id' => $this->task_id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created' => $this->created,
            'changed' => $this->changed,
        ]);

        return $dataProvider;
    }
}
