<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Adsence;

/**
 * AdsenceSearch represents the model behind the search form of `app\models\Adsence`.
 */
class AdsenceSearch extends Adsence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'category', 'state', 'products'], 'integer'],
            [['autorname', 'title', 'text', 'adsimg', 'adsphone', 'adsphone1', 'email', 'created_at', 'updated_at', 'metatitle', 'metakey', 'metadesc'], 'safe'],
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
        $query = Adsence::find();

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
            'userid' => $this->userid,
            'category' => $this->category,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'products' => $this->products,
        ]);

        $query->andFilterWhere(['like', 'autorname', $this->autorname])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'adsimg', $this->adsimg])
            ->andFilterWhere(['like', 'adsphone', $this->adsphone])
            ->andFilterWhere(['like', 'adsphone1', $this->adsphone1])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'metatitle', $this->metatitle])
            ->andFilterWhere(['like', 'metakey', $this->metakey])
            ->andFilterWhere(['like', 'metadesc', $this->metadesc]);

        return $dataProvider;
    }
}
