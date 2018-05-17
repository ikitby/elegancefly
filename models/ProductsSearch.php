<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'price', 'limit', 'hits', 'sales'], 'integer'],
            [['title'], 'string'],
            [['file', 'tags', 'photos', 'themes', 'created_at'], 'safe'],
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
        $query = Products::find();

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
            'title' => $this->title,
            'price' => $this->price,
            'limit' => $this->limit,
            'hits' => $this->hits,
            'sales' => $this->sales,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'photos', $this->photos])
            ->andFilterWhere(['like', 'themes', $this->themes]);

        return $dataProvider;
    }
}
