<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UsersSearch represents the model behind the search form of `app\models\User`.
 */
class UsersSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'usertype', 'percent', 'state', 'sales'], 'integer'],
            [['username', 'name', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'created_at', 'updated_at', 'photo', 'birthday', 'country', 'languages', 'fbpage', 'vkpage', 'inpage', 'tumblrpage', 'youtubepage', 'role', 'rate_c'], 'safe'],
            [['rate'], 'number'],
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
        $query = User::find()->joinWith('userLevel');

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
            'status' => $this->status,
            'usertype' => $this->usertype,
            'percent' => $this->percent,
            'state' => $this->state,
            'rate' => $this->rate,
            'sales' => $this->sales,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'languages', $this->languages])
            ->andFilterWhere(['like', 'fbpage', $this->fbpage])
            ->andFilterWhere(['like', 'vkpage', $this->vkpage])
            ->andFilterWhere(['like', 'inpage', $this->inpage])
            ->andFilterWhere(['like', 'tumblrpage', $this->tumblrpage])
            ->andFilterWhere(['like', 'youtubepage', $this->youtubepage])
            ->andFilterWhere(['in', 'auth_assignment.item_name', $this->role])
            ->andFilterWhere(['like', 'rate_c', $this->rate_c]);

        return $dataProvider;
    }
}
