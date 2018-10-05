<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Promotions;

/**
 * PromotionsSearch represents the model behind the search form of `app\models\Promotions`.
 */
class PromotionsSearch extends Promotions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'action_start', 'action_end', 'action_percent', 'action_autor', 'action_state'], 'integer'],
            [['action_title', 'action_descr', 'action_catergories', 'action_userroles', 'action_mailtext'], 'safe'],
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
        $query = Promotions::find();

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
            'created_at' => $this->created_at,
            'action_start' => $this->action_start,
            'action_end' => $this->action_end,
            'action_percent' => $this->action_percent,
            'action_autor' => $this->action_autor,
            'action_state' => $this->action_state,
        ]);

        $query->andFilterWhere(['like', 'action_title', $this->action_title])
            ->andFilterWhere(['like', 'action_descr', $this->action_descr])
            ->andFilterWhere(['like', 'action_catergories', $this->action_catergories])
            ->andFilterWhere(['like', 'action_userroles', $this->action_userroles])
            ->andFilterWhere(['like', 'action_mailtext', $this->action_mailtext]);

        return $dataProvider;
    }
}
