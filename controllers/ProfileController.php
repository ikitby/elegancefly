<?php

namespace app\controllers;

use app\models\ImageUpload;
use app\models\Prodlimit;
use app\models\Products;
use app\models\Transaction;
use app\models\Userevent;
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
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
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

    public $layout = 'profile';
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
                    'getcache' => ['POST'],
                    'upgrade' => ['POST'],
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

            if ($price = $querypost['price']) {
            $price = str_replace(",",".", $price);
            $model->price = $price;
            }
            if (User::Can('canResaleForResale') && $model->category == 2) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            $themes = $querypost['themes'];
            $model->saveThems($themes);

            $tags = $querypost['tags'];
            $model->saveTags($tags);

            $model->save(false);
            return $this->redirect(['myprojects', 'id' => $model->id]);
        }

        return $this->render('updateproject', ['model' => $model]);
    }

    public function actionDeposite()
    {
        $count = Yii::$app->request->post('count');
        $cid = Yii::$app->request->get('cid');
        $success = Yii::$app->request->get('success');
        $paymentId = Yii::$app->request->get('paymentId');
        $PayerID = Yii::$app->request->get('PayerID');
        $token = Yii::$app->request->get('token');

        if ($success) {

            if (!isset($success) && $paymentId && $PayerID)
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            if ($success == true && $cid)
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

                try {
                    $result = $payment->execute($execute, $apiContext);
                    Transaction::ApprowTranaction($cid);

                    Yii::$app->session->setFlash('success', 'Your account on the site has been successfully replenished! Thank you.'); //записываем в сессию сообщение для результата

                    return $this->redirect(['/profile']);


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

            $product = 'Replenishment of personal account on the site '; //Тенстовое название продукта
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

            $cid = Transaction::SetDeposite( 'Paypal', $price); //Создаем транзакцию по умолчанию и возвращаем ее уникальный cid

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(Url::toRoute(['/profile/deposite', 'success' => true, 'cid' => $cid], true))
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

            return Yii::$app->response->redirect($approvalUrl);
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
                        $price_limit = Yii::$app->request->post('Prodlimit')['price'];

                            $price_limit = str_replace(",",".",$price_limit);
                            $model->limit = Yii::$app->request->post('Prodlimit')['limit'];
                            $model->price = $price_limit;
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

        $payments = Transaction::find()->where(['action_user' => $id, 'status' =>1]);
        $allpayments = $payments;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => ProfileController::STATUS_PAGESIZE,
                'totalCount'        => $payments->count()
            ]
        );

        $payments = Transaction::find()
            ->where(['action_user' => $id, 'status' =>1])
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

    /*
     * Блок вывод денег
     */
    public function actionGetcache ()
    {

        $requestDelay = Yii::$app->params['requestDelay'];
        $minLimitCasheMoney = Yii::$app->params['minLimitCasheMoney'];
        $currentTime = time();

        //Проверим может ли пользователь по балансу сделать запрос
        $userBalance = Transaction::getUserBalance(Yii::$app->user->id);
        $user = User::findOne(Yii::$app->user->id);
        if ($userBalance >= $minLimitCasheMoney) { //Если баланс соответствуюе лимитам - разрешаем запрос

            //Проверка наличия запроса в базе (Берем последний запрос с неактивным статусом и проверяем когда он был)
            //$request = Userevent::find()->where(['event_type' => 'casherequest', 'event_progress' => 0])->orderBy(['event_time' => SORT_DESC])->one();
            $request = $this->userHaveRequest('casherequest', 0);

            $event_time = strtotime($request->event_time); //время последнего неподтвержденного события
            if (!$request) {//Есдли нет запроса - создаем.
                // установка события о новом запросе. если
                //-----------------------------------------------------------------

                $userEvent = new Userevent();
                $userEvent->setLog(Yii::$app->user->id, 'casherequest', 'Заявка на вывод <span class="label label-warning">'.$userBalance.'$</span>', '0');

                //-----------------------------------------------------------------
            }
            // Если пользователь еще не отправлял запроса или отправлял, но со времени отправки
            // прошло больше времени чем разрешено для отправки повторного запроса и при этом запрос
            // еще не был закрыт - отправляем новое писмо ответственным и обновляем запись
            if ($currentTime - $event_time > $requestDelay) {

                if (!empty($request)) {
                    $request->event_time = date('Y-m-d H:i:s');//обновляем дату запроса
                    $request->save();//Сохраняем
                }

                $mail_admins = User::getUsersByIds(User::UsersByPermission('canReceiveCasheMail'));

                $messages = [];
                foreach ($mail_admins as $mailadmin) {
                    $messages[] = Yii::$app->mailer->compose('userCasheMail', ['$userBalance' => $userBalance, 'user' => $user])
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (Запрос от '.$user->username.' на обналичку).'])
                        ->setTo($mailadmin->email)
                        ->setSubject('Запрос выводе данег от '.$user->username);
                }
                Yii::$app->mailer->sendMultiple($messages);

                Return "OK отправляем письмо";

            } else {
                $message = "Вы уже отправавляли заявку ".$request->event_time;
                Return $message;
            }


        } else {

            return ('You have not enough money for this action');
        }


    }

    /*
     * Обновление профиля до более высокого уровня
     */
    public function actionUpgrade()
    {
        $userid = Yii::$app->user->id;


        $user = User::getUsersByIds(Yii::$app->user->id);
        $requestDelay = Yii::$app->params['requestDelay']; //глобальная задержка между запросами
        $currentTime = time(); //текущее время

        if (User::Can('canUpgradeProfile') && Yii::$app->authManager->getRolesByUser($userid)["User"]) {
            //Проверяем наличие второго этапа!
            if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
                $imPainter = Yii::$app->request->POST('imPainter');
                if ($imPainter == 'ok') {

                    //$request = Userevent::find()->where(['event_type' => 'profileupdate', 'event_progress' => 0])->orderBy(['event_time' => SORT_DESC])->one();

                    $request = $this->userHaveRequest('profileupdate', 0);

                    $event_time = strtotime($request->event_time); //время последнего неподтвержденного события

                    $user = User::getById(Yii::$app->user->id);
                    $userName = ($user->name) ? $user->name : $user->username;

                    if (!$request) {//Если нет запроса - создаем.
                        //тут вызываем событие запроса!
                        //-----------------------------------------------------------------

                        $userEvent = new Userevent();
                        $userEvent->setLog(Yii::$app->user->id, 'profileupdate', 'Запрос от <span class="nusername">'.$userName.'</span> на профиль художника', '0');

                        //-----------------------------------------------------------------

                        $this->sendAdminMail('profileupdate', '0', 'Запрос от <span class="nusername">'.$userName.'</span> на профиль художника');
                        return $imPainter;
                    }
                   //---------------------------------!!!!!!!!!!
                    if ($request && $currentTime - $event_time > $requestDelay) {

                        $request->event_time = date('Y-m-d H:i:s');//обновляем дату запроса
                        $request->save();//Сохраняем

                        // Напомним еще раз письмом
                        $this->sendAdminMail('profileupdate', '0', 'Запрос от <span class="nusername">'.$userName.'</span> на профиль художника');
                        return $imPainter;
                    }

                }
                return $this->renderPartial('usernote', [
                    'user' => $user[0]
                ]);
            }
        } elseif (User::Can('canUpgradeProfile') && Yii::$app->authManager->getRolesByUser($userid)["Painter"]) {
            if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
               // $imCreator = Yii::$app->request->POST('imCreator');

                $request = $this->userHaveRequest('profileupdate', 0);

                $event_time = strtotime($request->event_time); //время последнего неподтвержденного события

                if (!$request) {//Если нет запроса - создаем.
                    //тут вызываем событие запроса!
                    //-----------------------------------------------------------------
                    $user = User::getById(Yii::$app->user->id);
                    $userName = ($user->name) ? $user->name : $user->username;

                    $userEvent = new Userevent();
                    $userEvent->setLog(Yii::$app->user->id, 'profileupdate', 'Запрос от <span class="nusername">'.$userName.'</span> на профиль творца', '0');

                    //-----------------------------------------------------------------

                    $this->sendAdminMail('profileupdate', '0', 'Запрос от <span class="nusername">'.$userName.'</span> на профиль творца');
                    return 'ok';
                }
                //---------------------------------!!!!!!!!!!
                if ($request && $currentTime - $event_time > $requestDelay) {

                    $user = User::getById(Yii::$app->user->id);
                    $userName = ($user->name) ? $user->name : $user->username;
                    $request->event_time = date('Y-m-d H:i:s');//обновляем дату запроса
                    $request->save();//Сохраняем

                    // Напомним еще раз письмом
                    $this->sendAdminMail('profileupdate', '0', 'Повторный запрос от <span class="nusername">'.$userName.'</span> на профиль творца');
                    return 'ok';
                }
            }
            return true;
        }
        Yii::$app->session->setFlash('warning', 'You have already submitted a request!');
        Yii::$app->getResponse()->redirect( '/profile/upgrade' )->send(); # Укажите ссылку
        Yii::$app->end();
        return false;
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

    /*
    *   Отправка письма администратору ---------------------------------
    */
    private function sendAdminMail ($event_type = 'user', $event_progress = 0, $event_desc='Запрос на смену профиля') {

        $requestDelay = Yii::$app->params['requestDelay']; //глобальная задержка между запросами
        $currentTime = time(); //текущее время
        $user = User::findOne(Yii::$app->user->id); //текущий пользователь


        $mail_admins = User::getUsersByIds(User::UsersByPermission('canApprowPainterMail')); //Берем пользователей, что могут получать уведомления о запросах художников

        $messages = [];
        foreach ($mail_admins as $mailadmin) {
            $messages[] = Yii::$app->mailer->compose('userCangeProfileMail', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (Запрос '.$user->username.' на смену профиля).'])
                ->setTo($mailadmin->email)
                ->setSubject($event_desc.' от '.$user->username);
        }
        return Yii::$app->mailer->sendMultiple($messages);
    }

    public static function userHaveRequest($eventtype='user', $eventprogress='0') {
        return Userevent::find()->where(['event_user' => Yii::$app->user->id, 'event_type' => $eventtype, 'event_progress' => $eventprogress])->orderBy(['event_time' => SORT_DESC])->one();
    }

    //
    public static function userCanNewRequest($eventtype='profileupdate', $eventprogress='0') {
        $requestDelay = Yii::$app->params['requestDelay']; //глобальная задержка между запросами
        $currentTime = time(); //текущее время
        $event = Userevent::find()->where(['event_user' => Yii::$app->user->id, 'event_type' => $eventtype, 'event_progress' => $eventprogress])->orderBy(['event_time' => SORT_DESC])->one();
        $event_time = strtotime($event['event_time']);
        if (!$event || ($event && $currentTime - $event_time > $requestDelay)) {
            return true;
        }
        return false;
    }
}
