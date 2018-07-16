<?php

namespace app\controllers;

use app\models\ImageUpload;
use app\models\Prodlimit;
use app\models\Products;
use app\models\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends AppController
{

    const STATUS_PAGESIZE = 20;

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
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {return $this->redirect(['/login']);}
        $id = Yii::$app->user->id;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionPurchases()
    {
        $id = $this->checkAccess();

        $purchases = Transaction::find()->where(['action_user' => $id]);
        $allpayments = $purchases;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => ProfileController::STATUS_PAGESIZE,
                'totalCount'        => $purchases->count()
            ]
        );

        $purchases = Transaction::find()
            ->where(['action_user' => $id, 'type' => 0])
            ->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('purchases', [
            'purchases' => $purchases,
            'pagination' => $pagination,
            'allpayments' => $allpayments
        ]);

    }

    public function actionMyprojects()
    {
        $id = $this->checkAccess();
        $projects = Products::find()->where(['user_id' => Yii::$app->user->id]);
        $projectsall = $projects;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $projects->count()
            ]
        );

        $projects = Products::find()
            ->where(['user_id' => Yii::$app->user->id,'deleted' => 0])
            ->with(['user', 'catprod'])
            ->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('myprojects', [
            'projects'      => $projects,
            'projectsall'   => $projectsall,
            'pagination'    => $pagination,
        ]);
    }

    public function actionUpdateproject($id)
    {

        $model = Products::findOne($id);

        $model->themes = $model->getTems(); //Загоняем в модельку связаные темы
        $model->tags = $model->getItemtags(); //Загоняем в модельку связаные теги

        if ($model->load(Yii::$app->request->post())) //обработка категорий и тегов
        {
            $querypost = Yii::$app->request->post('Products');
            $themes = $querypost['themes'];
            $model->saveThems($themes);

            $tags = $querypost['tags'];
            $model->saveTags($tags);
            $model->save(false);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['myprojects', 'id' => $model->id]);
        }

        return $this->render('updateproject', [
            'model' => $model,
        ]);
    }

    public function actionDeposite()
    {


        $count = Yii::$app->request->get('count');
        $gateway = Yii::$app->request->get('gateway');
        $success = Yii::$app->request->get('success');

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AcNgvESyw-HTyZ7cwAk2E7CMl2Qyqt99PUHOCqabZdpQKDvwza3v5ySpOTnBbfGGcJkDdol9_LRCvKa5',     // ClientID
                'ELFAsnIMM1_CsPZTVEzC0MktzrtcPY81-DMh0C_RxAH9Z4Pu-fZVuIcBdLKCIeEOkrEGRg2fUOYtAECm'      // ClientSecret
            )
        );

        //$apiPaypal = Yii::$app->cm;


        //dump($apiPaypal);
        //dump($apiContext);
        //die();


        if ($count) {
            $cartprod = $this->getCartItems();

            return $this->render('deposit_' . $gateway, [
                'cartprod' => $cartprod,
                'gateway' => $gateway
            ]);
        } elseif ($success == true) {

            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    'AcNgvESyw-HTyZ7cwAk2E7CMl2Qyqt99PUHOCqabZdpQKDvwza3v5ySpOTnBbfGGcJkDdol9_LRCvKa5',     // ClientID
                    'ELFAsnIMM1_CsPZTVEzC0MktzrtcPY81-DMh0C_RxAH9Z4Pu-fZVuIcBdLKCIeEOkrEGRg2fUOYtAECm'      // ClientSecret
                )
            );



            $paymentId = Yii::$app->request->get('paymentId');
            $token = Yii::$app->request->get('token');
            $PayerID = Yii::$app->request->get('PayerID');

            if (!isset($success) && $paymentId && $PayerID)
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            $payment = Payment::get($paymentId, $apiContext);
            $execute = new PaymentExecution();
            $execute->setPayerId($PayerID);

            try
            {
                $result = $payment->execute($execute, $apiContext);


                //Блок раздачи плюшек всем авторам при оплате кокупки paypal.

                $transaction_id = UuidHelper::uuid();
                $cartItems = $this->getCartItems();

                foreach ($cartItems as $item) {

                    //----- Обработка стоимости
                    $itemprice = $item->price; //Полная цена товара
                    $autorProcent = $itemprice*0.5;
                    //----- Обработка стоимости

                    //--------------------- Start Trasnsaction --------------------
                    $paymenttransaction = Transaction::getDb()->beginTransaction();
                    try {
//--------------------- Start Trasnsaction --------------------
                        //Отдаем денежку автору за работу
                        $current_balance = $this->getUserBalance($item->seller_id); //баланс художника
                        $transaction = new Transaction();
                        $transaction->action_id = $transaction_id;
                        $transaction->action_user = $item->seller_id;
                        $transaction->source_payment = Yii::$app->user->id;
                        $transaction->amount = $autorProcent;
                        $transaction->c_balance = $current_balance + $autorProcent; //пополняем запись текущего баланска в транзакции
                        $transaction->type = 1; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса)
                        $transaction->prod_id = $item->product_id;
                        $transaction->save();
//--------------------- End Trasnsaction --------------------
                        $paymenttransaction->commit();
                    } catch (\Exception $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    }
//--------------------- End Trasnsaction --------------------

                }

                return $this->redirect(['/profile']);

            } catch (Exception $e) {
                throw new NotFoundHttpException($e);
            }

        }
        throw new NotFoundHttpException('The requested page does not exist.');




    }



    public function actionSetlimit() //Установка лимита продаж
    {
        if (!Yii::$app->user->isGuest) {
            $id = Yii::$app->request->post('id');

            $user_id = Yii::$app->user->id;
            //$model = Products::findOne($id);
            $model = Prodlimit::findOne($id);

            if (Yii::$app->request->post('Prodlimit')['id'])
            {
                $model = Prodlimit::findOne(Yii::$app->request->post('Prodlimit')['id']);

                if ($model->user_id == $user_id && Products::checkLimit($id)) {
                    if (Transaction::getProdSales($model->id) == 0)
                    {
                        $model->limit = Yii::$app->request->post('Prodlimit')['limit'];
                        $model->price = Yii::$app->request->post('Prodlimit')['price'];
                        $model->save(false);
                    }
                    return $this->redirect(Yii::$app->request->referrer);

                }
            } else {
                return $this->renderPartial('limitform', [
                    'model' => $model
                ]);
            }

        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }


    public function actionPayments()
    {
        $id = $this->checkAccess();

        $payments = Transaction::find()->where(['action_user' => $id]);
        $allpayments = $payments;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => ProfileController::STATUS_PAGESIZE,
                'totalCount'        => $payments->count()
            ]
        );

        $payments = Transaction::find()
            ->where(['action_user' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('payments', [
            'payments' => $payments,
            'pagination' => $pagination,
            'allpayments' => $allpayments
        ]);
    }

    public function actionEdit()
    {
        $id = $this->checkAccess();

        $model = $this->findModel($id);
        $imgmodel = new ImageUpload();
        $currentphoto = $model->photo;

        if ($model->load(Yii::$app->request->post())) {

            ($model->photo) ? $model->photo : $model->photo  = $currentphoto; //если форма фото пустая - возвращаем значение текущего фото
            //$model->user_phones = implode(",", $model->user_phones);   //обрабатываем массив телефонов в строку
            $file = UploadedFile::getInstance($model, 'photo'); //цепляем из нашей модельки файл по его полю
            $model->save();
            if ($file) {
                $model->saveImage($imgmodel->uploadImage($file, $currentphoto, 'user')); //запускаем сохранение файла в базе с именем сохраненного файла
            };

            return $this->redirect(['/profile']);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPublishproject()
    {
        $project_id = Yii::$app->request->post('id');
        //$project_id = 58;
        //return json_encode($project_id);
        $user_id = $this->checkAccess();
        $product = Products::find()->where(['id' => $project_id, 'user_id' => $user_id])->one();

        if ($product) {
            $product->state = ($product->state == true) ?  false: true;
            $product->save();
            return json_encode($product->state);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function getTransactions()
    {
        return Transaction::getUserTransactions(Yii::$app->user->id);
    }

    private function checkAccess()
    {
        if (Yii::$app->user->isGuest) {return $this->redirect(['/login']);}
        return $id = Yii::$app->user->id;
    }
}
