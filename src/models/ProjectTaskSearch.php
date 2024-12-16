<?php

namespace hesabro\trello\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use hesabro\trello\models\ProjectTask;

/**
 * ProjectTaskSearch represents the model behind the search form of `hesabro\trello\models\ProjectTask`.
 */
class ProjectTaskSearch extends ProjectTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'update_id', 'start', 'end', 't_order', 'status', 'created', 'changed'], 'integer'],
            [['task_name'], 'safe'],
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
        $query = ProjectTask::find();

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
            'start' => $this->start,
            'end' => $this->end,
            't_order' => $this->t_order,
            'status' => $this->status,
            'created' => $this->created,
            'changed' => $this->changed,
        ]);

        $query->andFilterWhere(['like', 'task_name', $this->task_name]);

        return $dataProvider;
    }
}
