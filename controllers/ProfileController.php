<?php

namespace app\controllers;

use app\models\ImageUpload;
use app\models\Prodlimit;
use app\models\Products;
use app\models\Transaction;
use Exception;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
//use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use app\models\User;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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

        $count = Yii::$app->request->post('count');
        $gateway = Yii::$app->request->get('gateway');
        $success = Yii::$app->request->get('success');
        $paymentId = Yii::$app->request->get('paymentId');
        $PayerID = Yii::$app->request->get('PayerID');
        $token = Yii::$app->request->get('token');

        if ($success) {
            if (!isset($success) && $paymentId && $PayerID)
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            if ($success == true)
            {
                // Инициализируем paypal
                $apiPaypal = Yii::$app->cm;
                $apiContext = new apiContext(
                    new OAuthTokenCredential($apiPaypal->client_id, $apiPaypal->client_secret)
                );
                // Инициализируем paypal

                $payment = Payment::get($paymentId, $apiContext);
                $execute = new PaymentExecution();
                $execute->setPayerId($PayerID);

                $checkoutDetails = $payment->getToken();

                $amount = '';
                dump($checkoutDetails );
                dump($payment);
                dump($payment->getTransactions->getAmount);

                die();

                try {

                    $result = $payment->execute($execute, $apiContext);

                } catch (Exception $e) {
                    $data = json_decode($e->getData());

                    throw new NotFoundHttpException($data->message);
                }

                throw new NotFoundHttpException('The requested page does not exist.');
            }

        } else {
            // Инициализируем paypal
            $apiPaypal = Yii::$app->cm;
            $apiContext = new apiContext(
                new OAuthTokenCredential($apiPaypal->client_id, $apiPaypal->client_secret)
            );
            // Инициализируем paypal

            $product = 'Пополнение личного счета на сайте '; //Тенстовое название продукта
            $price = $count; //Полдучаем стоимость товара в данном случае полную стоимость товаров в корзине для теста
            $shipping = 0; //если доставка платная - указываем ее

            $total = $price + $shipping; //калькулируем конечную стоимость с учетом доставки

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item = new Item();
            $item->setName($product)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($price);

            $itemList = new ItemList();
            $itemList->setItems([$item]);

            $details = new Details();
            $details->setShipping($shipping)
                ->setSubtotal($price);

            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($total)
                ->setDetails($details);

            $transaction = new \PayPal\Api\Transaction(); // Вот тут мой прокол. Из за одноименной модели Транзакций - я не могу глобально подключить пайпал
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription('Payment')
                ->setInvoiceNumber(uniqid());

// Блок для генерации ключа-идетификатора корзины (его добавим в базу ко всем записям корзины и к ReturnUrl - по нему дополнительно проверим принадлежность корзины оплате)

            $buyer_id = Yii::$app->user->id;
            $basket_uniq_id = UuidHelper::uuid();

// Конец блока генерации ключа

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(Url::toRoute(['/profile/deposite', 'success' => true], true))
                ->setCancelUrl(Url::toRoute(['/profile/deposite', 'success' => false], true));

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $payment->create($apiContext);
            } catch (Exception $e) {
                dump($e);
            }

            $approvalUrl = $payment->getApprovalLink();

            return $approvalUrl;

            //return Yii::$app->response->redirect($approvalUrl);
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
