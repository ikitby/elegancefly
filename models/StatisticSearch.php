<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * StatisticSearch represents the model behind the search form of `app\models\Products`.
 */
class StatisticSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'limit', 'hits', 'tatng_votes', 'active_promo'], 'integer'],
            [['title', 'file', 'tags', 'photos', 'project_info', 'project_path', 'themes', 'themes_index', 'created_at'], 'safe'],
            [['file_size', 'price', 'sales', 'rating'], 'number'],
            [['state', 'deleted'], 'boolean'],
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
        $userid = Yii::$app->user->id;
        $query = Products::find()->join('LEFT JOIN','transaction', 'transaction.prod_id = products.id')
           ->andWhere('transaction.action_user = '.$userid.'');

        //$query = Products::find()->leftJoin('transaction', ['transaction.prod_id' => 'products.id', 'transaction.action_user' => Yii::$app->user->id, 'transaction.type' => 1]);
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
            'user_id' => Yii::$app->user->id,
            //'id' => $this->id,
            //'type' => 0,
          //  'category' => $this->category,
          //  'category' => $this->category,
          //  'file_size' => $this->file_size,
          //  'price' => $this->price,
          //  'limit' => $this->limit,
          //  'hits' => $this->hits,
          //  'sales' => $this->sales,
          //  'rating' => $this->rating,
          //  'tatng_votes' => $this->tatng_votes,
          //  'state' => $this->state,
          //  'deleted' => $this->deleted,
          //  'created_at' => $this->created_at,
          //  'active_promo' => $this->active_promo,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
           // ->andFilterWhere(['like', 'file', $this->file])
           // ->andFilterWhere(['like', 'tags', $this->tags])
           // ->andFilterWhere(['like', 'photos', $this->photos])
           // ->andFilterWhere(['like', 'project_info', $this->project_info])
           // ->andFilterWhere(['like', 'project_path', $this->project_path])
           // ->andFilterWhere(['like', 'themes', $this->themes])
           //->andFilterWhere(['transaction.type' => 1]);
           // ->andFilterWhere(['like', 'themes_index', $this->themes_index]);

        return $dataProvider;
    }
}
