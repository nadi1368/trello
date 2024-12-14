<?php

namespace hesabro\trello\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use hesabro\trello\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `hesabro\trello\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'update_id', 'user_id', 'project_status', 'status', 'created', 'changed'], 'integer'],
            [['project_name'], 'safe'],
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
        $query = Project::find();

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
            'user_id' => $this->user_id,
            'project_status' => $this->project_status,
            'status' => $this->status,
            'created' => $this->created,
            'changed' => $this->changed,
        ]);

        $query->andFilterWhere(['like', 'project_name', $this->project_name]);

        return $dataProvider;
    }
}
