<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductsAuthor extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'limit', 'hits', 'rating'], 'integer'],
            [['title', 'file', 'tags', 'photos', 'project_info', 'project_path', 'themes', 'themes_index', 'created_at'], 'safe'],
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
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => User::find()->where(['username' => Yii::$app->request->get('painter')])->select('id')->one(),
            'category' => $this->category,
            'state' => 1,
            'deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['in', 'id', ProjectTags::getArtIdsFromTagId($this->tags)])
            ->andFilterWhere(['in', 'id', ProjectThems::getArtIdsFromTemaId($this->themes)])
            //->andFilterWhere(['like', 'project_info', $this->project_info])
            //->andFilterWhere(['like', 'project_path', $this->project_path])
            //->andFilterWhere(['like', 'themes', $this->themes])
            ->andFilterWhere(['like', 'themes_index', $this->themes_index]);

        return $dataProvider;
    }
}
