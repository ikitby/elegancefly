<?php

namespace app\controllers;

use app\models\Products;
use Yii;
use app\models\Promotions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PromotionsController implements the CRUD actions for Promotions model.
 */
class PromoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'accept' => ['POST'],
                    'reject' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Promotions models.
     * @return mixed
     */
    public function actionIndex()
    {
        return false;
    }

    public function actionAccept()
    {
        $id = Yii::$app->request->post('id');
        $pid = Yii::$app->request->post('pid');

        $product = Products::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]); //Ишем конкретно проект запросившего пользвоателя по id
        $promo = Promotions::getSaleUser($product); //Получаем акцию рпоекста (с проверкой разрешена ли ему)

        if (!$product || !$promo) return false;

        $product->active_promo = $pid; //Устанавливаем проекту активную акцию по ID
        $product->save();

        return "ok";
    }

    public function actionReject()
    {
        $id = Yii::$app->request->post('id');
        $pid = Yii::$app->request->post('pid');

        $product = Products::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]); //Ишем конкретно проект запросившего пользвоателя по id
        $promo = Promotions::getSaleUser($product); //Получаем акцию рпоекста (с проверкой разрешена ли ему)

        if (!$product || !$promo) return false;

        $product->active_promo = NULL; //Устанавливаем проукту активную акцию по ID
        $product->save();

        return "no";
    }

    /**
     * Updates an existing Promotions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $loadmodel = Yii::$app->request->post();

        $model->action_catergories = $model->getPromocats(); //Загоняем в модельку связаные темы

        if ($model->load($loadmodel)){

            $promotions = Yii::$app->request->post('Promotions');

            $model->load(Yii::$app->request->post());

            $categories = $promotions['action_catergories'];
            $model->savePromocat($categories);

            if ($model->load($loadmodel) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Promotions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
