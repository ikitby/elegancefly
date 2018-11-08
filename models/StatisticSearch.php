<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;
use yii\data\ArrayDataProvider;

/**
 * StatisticSearch represents the model behind the search form of `app\models\Products`.
 */
class StatisticSearch extends Products
{
    /**
     * {@inheritdoc}
     */

    public $title;
    public $date;
    public $sales;
    public $amount;
    public $transactionAmount;
    public $transactionCount;

    public function rules()
    {
        return [
            [['id', 'user_id', 'category', 'limit', 'hits', 'tatng_votes', 'active_promo', 'sales', 'amount'], 'integer'],
            [['title', 'file', 'tags', 'photos', 'project_info', 'project_path', 'themes', 'themes_index', 'created_at'], 'safe'],
            [['file_size', 'price', 'sales', 'rating'], 'number'],
            [['state', 'deleted'], 'boolean'],
            [['transactionAmount', 'transactionCount'], 'safe'],
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

    public function newdate($date) {
        $date = strtotime($date); // переводит из строки в дату
        $date = date("Y-m-d", $date);
        return $date;
    }

    public function search($params)
    {
        $from_date = Yii::$app->request->get('from_date'); //Мнинимальная дкта
        $to_date = Yii::$app->request->get('to_date'); //Максимальная дата

        $from_date = (!empty($from_date))? $from_date : $this->newdate(Transaction::find()->select(['created_at', 'id'])->where(['action_user' => Yii::$app->user->id, 'type' => 1])->indexBy('created_at')->min('created_at'));
        $to_date = (!empty($to_date))? $to_date : $this->newdate(date("Y-m-d"));
        //dump($from_date);
        $query = Products::find();
        $subQuery = Transaction::find()
            ->select(['prod_id, SUM(amount) AS transaction_amount, COUNT(prod_id) AS transaction_count'])
            ->where(['type' => 1, 'action_user' => Yii::$app->user->id])
            ->andWhere(['between', 'created_at', $from_date, $to_date.' 23:59:59'])
            ->groupBy('prod_id');
        $query->leftJoin([
            'orderSum'=>$subQuery
        ], 'orderSum.prod_id = id')
        ->where(['>','orderSum.transaction_amount', 0])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'title',
                'date',
                'transactionCount'=>[
                    'asc'=>['orderSum.transaction_count'=>SORT_ASC],
                    'desc'=>['orderSum.transaction_count'=>SORT_DESC],
                    'label'=>'Count'
                ],
                'transactionAmount'=>[
                    'asc'=>['orderSum.transaction_amount'=>SORT_ASC],
                    'desc'=>['orderSum.transaction_amount'=>SORT_DESC],
                    'label'=>'Sum'
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
/*
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //$query->joinWith(['products']);
            return $dataProvider;
        }
*/
        $query->andFilterWhere([
            'id'=>$this->id,

        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        // filter by order amount
        $query->andWhere(['orderSum.transaction_amount'=>$this->transactionAmount]);

        $query->andFilterWhere([
            'user_id' => Yii::$app->user->id,
            //'transaction.action_user' => Yii::$app->user->id,
            //'transaction.type' => 1,
            //'title' => $this->title,
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

        $query->andFilterWhere(['like', 'title', $this->title])
           ->andFilterWhere(['between', 'transaction.created_at', $from_date, $to_date.' 23:59:59']);
           // ->andFilterWhere(['like', 'tags', $this->tags])
           // ->andFilterWhere(['like', 'photos', $this->photos])
           // ->andFilterWhere(['like', 'project_info', $this->project_info])
           // ->andFilterWhere(['like', 'project_path', $this->project_path])
           // ->andFilterWhere(['like', 'themes', $this->themes])
           //->andFilterWhere(['transaction.type' => 1]);
           // ->andFilterWhere(['like', 'themes_index', $this->themes_index]);
        //dump($query);

        return $dataProvider;

    }
}
