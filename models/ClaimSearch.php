<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Claim;

/**
 * ClaimSearch represents the model behind the search form of `app\models\Claim`.
 */
class ClaimSearch extends Claim
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'purchase_id', 'claim_date', 'resolution_date', 'created_at', 'updated_at'], 'integer'],
            [['description', 'claim_type', 'status', 'resolution_notes'], 'safe'],
            [['amount_claimed', 'amount_resolved'], 'number'],
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
        $query = Claim::find()->joinWith(['purchase', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['claim_date' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'claims.id' => $this->id,
            'claims.user_id' => $this->user_id,
            'claims.purchase_id' => $this->purchase_id,
            'claims.claim_date' => $this->claim_date,
            'claims.resolution_date' => $this->resolution_date,
            'claims.amount_claimed' => $this->amount_claimed,
            'claims.amount_resolved' => $this->amount_resolved,
            'claims.created_at' => $this->created_at,
            'claims.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'claims.description', $this->description])
            ->andFilterWhere(['like', 'claims.claim_type', $this->claim_type])
            ->andFilterWhere(['like', 'claims.status', $this->status])
            ->andFilterWhere(['like', 'claims.resolution_notes', $this->resolution_notes]);

        return $dataProvider;
    }
}
