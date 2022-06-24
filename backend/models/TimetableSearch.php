<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Timetable;

/**
 * TimetableSearch represents the model behind the search form of `common\models\Timetable`.
 */
class TimetableSearch extends Timetable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'date', 'time_from', 'time_to', 'place_id', 'user_id', 'is_active', 'deleted', 'position', 'created_at', 'updated_at'], 'integer'],
            [['unique_id', 'name', 'description', 'short_description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Timetable::find();

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
            'client_id' => $this->client_id,
            'date' => $this->date,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'place_id' => $this->place_id,
            'user_id' => $this->user_id,
            'is_active' => $this->is_active,
            'deleted' => $this->deleted,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unique_id', $this->unique_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'short_description', $this->short_description]);

        return $dataProvider;
    }
}
