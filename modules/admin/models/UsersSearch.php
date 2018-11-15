<?php

namespace app\modules\admin\models;

use app\models\Transaction;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * UsersSearch represents the model behind the search form of `app\models\User`.
 */
class UsersSearch extends User
{
    public $orderAmount;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'usertype', 'percent', 'state', 'sales'], 'integer'],
            [['username', 'name', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'created_at', 'updated_at', 'photo', 'birthday', 'country', 'languages', 'fbpage', 'vkpage', 'inpage', 'tumblrpage', 'youtubepage', 'role', 'rate_c'], 'safe'],
            [['rate'], 'number'],
            [['orderAmount'], 'safe']
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

        $subQuery = Transaction::find()
            ->select('action_user, SUM(amount) as order_amount')
            ->groupBy('action_user');
        $query->leftJoin([
            'orderSum'=>$subQuery
        ], 'orderSum.action_user = id');

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

        $dataProvider->setSort([
            'attributes'=>[
                'username'=>[
                    'asc'=>['username'=>SORT_ASC],
                    'desc'=>['username'=>SORT_DESC],
                    'label'=>'username'
                ],
                'name'=>[
                    'asc'=>['name'=>SORT_ASC],
                    'desc'=>['name'=>SORT_DESC],
                    'label'=>'name'
                ],
                'orderAmount'=>[
                    'asc'=>['orderSum.order_amount'=>SORT_ASC],
                    'desc'=>['orderSum.order_amount'=>SORT_DESC],
                    'label'=>'Баланс'
                ],
                'role'=>[
                    'asc'=>['role'=>SORT_ASC],
                    'desc'=>['role'=>SORT_DESC],
                    'label'=>'role'
                ],
                'percent'=>[
                    'asc'=>['percent'=>SORT_ASC],
                    'desc'=>['percent'=>SORT_DESC],
                    'label'=>'percent'
                ],
                'email'=>[
                    'asc'=>['email'=>SORT_ASC],
                    'desc'=>['email'=>SORT_DESC],
                    'label'=>'email'
                ]
            ]

        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            //'status' => $this->status,
            //'usertype' => $this->usertype,
            //'percent' => $this->percent,
            'status' => 10,
            //'rate' => $this->rate,
            //'sales' => $this->sales,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'percent', $this->percent])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'languages', $this->languages])
            ->andFilterWhere(['in', 'auth_assignment.item_name', $this->role])
            ->andFilterWhere(['like', 'rate_c', $this->rate_c]);

        return $dataProvider;
    }
}
