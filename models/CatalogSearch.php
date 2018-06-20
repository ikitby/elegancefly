<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;
use app\models\Themsprod;

/**
 * CatalogSearch represents the model behind the search form of `app\models\Products`.
 */
class CatalogSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'limit', 'themes', 'hits', 'rating'], 'integer'],
            [['title', 'file', 'tags', 'photos', 'project_info', 'project_path', 'created_at'], 'safe'],
            [['price', 'sales'], 'number'],
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
        //dump($this);
        //die();

                $query->andFilterWhere([
                    'id' => $this->id,
                    'user_id'   => $this->user_id,
                    'category'  => $this->category,
                    //'themes'    => $this->themes,
                    //'themes_index' => $this->themes,
                    'state'     => 1,
                    'deleted'   => 0,
                ]);

                //$query->andFilterWhere(['themes_index', $this->themes]);

                //dump($query);

                $query->andFilterWhere(['like', 'title', $this->title])
                    ->andFilterWhere(['like', 'file', $this->file])
                    ->andFilterWhere(['like', 'tags', $this->tags])
                    ->andFilterWhere(['like', 'photos', $this->photos])
                    ->andFilterWhere(['like', 'project_info', $this->project_info])
                    ->andFilterWhere(['in', 'themes', $this->themes])
                    ->andFilterWhere(['like', 'project_path', $this->project_path]);
                    //->andFilterWhere(['like', 'themes_index', json_decode($this->themes)]);
                    //->andFilterWhere(['like', 'themes', $this->themes]);


        return $dataProvider;
    }
}
