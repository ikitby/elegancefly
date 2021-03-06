<?php

namespace app\controllers;

use app\models\Catprod;
use app\models\ProductsSearch;
use app\models\Ratings;
use app\models\Tags;
use app\models\Themsprod;
use app\models\UploadProject;
use app\models\User;
use app\models\Userevent;
use Yii;
use app\models\Products;
use yii\data\Pagination;

use yii\helpers\ArrayHelper;

use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use dastanaron\translit\Translit;



/**
 * CatalogController implements the CRUD actions for Products model.
 */
class CatalogController extends AppController
{

    const STATUS_PAGESIZE = 36;

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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {

        $userid = Yii::$app->user->id;
        $userLever = Yii::$app->authManager->getRolesByUser($userid)["Admin"];

        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $products = Products::find()->where($dataProvider->query->where);
        $productsall = $products;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $products->count()
            ]
        );

        //-----------------------------------------------------------------
/*
        $userEvent = new Userevent();
        $userEvent->UsercCanReciveMoney(Yii::$app->user->id,'1');
*/
        //-----------------------------------------------------------------

        $products = Products::find()
            ->where($dataProvider->query->where)
            ->andWhere(['deleted' => 0])
            ->with(['user', 'catprod'])
            ->joinWith('transactions')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        /*
            $customers = Customer::find()
            ->joinWith('orders')
            ->where(['order.status' => Order::STATUS_ACTIVE])
            ->all();
        */


        return $this->render('index', [
            'products'      => $products,
            'productsall'   => $productsall,
            'pagination'    => $pagination,
            'searchModel'   => $searchModel,
            'dataProvider'   => $dataProvider
        ]);

    }


    public function actionPainter()
    {
        $painter = User::find()->where(['username' => Yii::$app->request->get('painter')])->select('id')->one();

        $products = Products::find()->where(['user_id' => $painter, 'state' => 1, 'deleted' => 0]);
        $productsall = $products;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $products->count()
            ]
        );

        $products = Products::find()
            ->where(['user_id' => $painter, 'state' => 1, 'deleted' => 0])
            ->with(['user', 'catprod'])
            ->joinWith('transactions')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        return $this->render('index', [
            'products'      => $products,
            'productsall'   => $productsall,
            'pagination'    => $pagination,
        ]);

    }


    public function actionShow()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $products = Products::find()->where($dataProvider->query->where);
        $productsall = $products;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $products->count()
            ]
        );

        $products = Products::find()
            ->where($dataProvider->query->where)
            ->andWhere(['deleted' => 0])
            ->with(['user', 'catprod'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'products'      => $products,
            'productsall'   => $productsall,
            'pagination'    => $pagination,
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
        ]);
    }

    public function actionCategory()
    {

        if (Yii::$app->request->get('id')) {

            $id = Yii::$app->request->get('id');

            $this->addHits($id);
            $model = $this->findModel($id);

            //$rating_count = $model->getRatings()->select('rating')->count();
            //$rating = $model->getRatings()->select('rating')->asArray()->all();

            $model->rating = $model->afterFind();

            //$rateUsers = $model->rateUsers; //Так можно получить оценивших материал пользователей
            return $this->render('view', [
                'model' => $model
            ]);

        } else {
            $products = Products::find()->where(['state' => 1, 'deleted' => 0]);
            $productsall = $products;

            $catalias = Yii::$app->request->get('catalias');
            $catid = Catprod::find()->where(['alias' => $catalias])->one()->id;

            $pagination = new Pagination(
                [
                    'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                    'totalCount'        => $products->count()
                ]
            );

            $products = Products::find()
                ->where([
                    'state' => 1,
                    'deleted' => 0,
                    'category' => $catid,
                ])
                ->with(['user', 'catprod', 'transactions'])
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            return $this->render('category', [
                'products'      => $products,
                'productsall'   => $productsall,
                'pagination'    => $pagination
            ]);
        }
    }

    public function actionTag()
    {
        $request = Yii::$app->request;
        $tagalias = $request->get('alias');

        $tagsprod = Tags::findOne(['alias' => $tagalias]);
        $tags = ArrayHelper::getColumn($tagsprod->products, 'id'); //получаем список id продуктов с неободимым тегом

        $products = Products::find()
            ->where([
                'state' => 1,
                'deleted' => 0,
                'id' => $tags,
            ]);

        $productsall = $products;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $products->count() //ограничиваем пагинацию по размеру массива тега
            ]
        );

        $products = Products::find()
            ->where([
                'state' => 1,
                'deleted' => 0,
                'id' => $tags,
            ])
            ->with('user')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'products'      => $products,
            'productsall'   => $productsall,
            'pagination'    => $pagination
        ]);

    }

    public function actionTema()
    {
        $request = Yii::$app->request;
        $temaalias = $request->get('alias');

        $temsprod = Themsprod::findOne(['alias' => $temaalias]);
        $tags = ArrayHelper::getColumn($temsprod->products, 'id'); //получаем список id продуктов с необходимым тегом

        $products = Products::find()
            ->where([
                'state' => 1,
                'deleted' => 0,
                'id' => $tags,
            ]);

        $productsall = $products;

        $pagination = new Pagination(
            [
                'defaultPageSize'   => CatalogController::STATUS_PAGESIZE,
                'totalCount'        => $products->count() //ограничиваем пагинацию по размеру массива тега
            ]
        );

        $products = Products::find()
            ->where([
                'state' => 1,
                'deleted' => 0,
                'id' => $tags,
            ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'products'      => $products,
            'productsall'   => $productsall,
            'pagination'    => $pagination
        ]);

    }

    public function actionRate($pid = 0, $rating = 0)
    {

        if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
            $rating = new Ratings();
            $request = Yii::$app->request;

            $pid = $request->post('pid');
            $rate = $request->post('rating');
/*
            $pid = 55;//delete
            $rate = 4;//delete

            return $rating->setRating($pid, $rate); //delete
*/
            if ($rate && $pid) {
                return $rating->setRating($pid, $rate);
            } else {
                return 'Not allowed action';
            }
        }
    }

    public function actionView($id)
    {

        $this->addHits($id);
        $model = $this->findModel($id);

        //$rating_count = $model->getRatings()->select('rating')->count();
        //$rating = $model->getRatings()->select('rating')->asArray()->all();

        $model->rating = $model->afterFind();

        //$rateUsers = $model->rateUsers; //Так можно получить оценивших материал пользователей
        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionAjaxfile()
    {
        $model = new Products();
        $filemodel = new UploadProject();

        $model->user_id = yii::$app->user->id;

        if (Yii::$app->request->post() && Yii::$app->request->isAjax) {

            $model->photos = '';

            $postfile = $_FILES['photos'];
            $file = new UploadedFile();
            $file->name = $postfile['name'];
            $file->tempName = $postfile['tempName'];
            $file->type = $postfile['type'];
            $file->size = $postfile['size'];
            $file->error = $postfile['error'];

            $file = UploadedFile::getInstanceByName('photos'); //Вот эта строчка не срабатывает. надо разобраться.

            $userfolder = $this->getUserFolder();

            if ( !file_exists($userfolder ) )//проверяем и если нет - создаем папку пользователя по его id
            {
                mkdir($userfolder, 755); //создаем папку проектов пользователя
            }

            $projectfolder = $this->translite($file->baseName) . '_' . strtolower(uniqid(md5($file->baseName)));

            if ($file) {

                $photosmodel = $filemodel->makeGalery($file);

                $model->saveProject($filemodel->uploadZip($file, $userfolder, $projectfolder, $model), $userfolder.'/'.$projectfolder.'/', $photosmodel); //запускаем сохранение файла в базе с именем сохраненного файла

            };

            //-----------------------------------------------------------------

            //event new user project
            $project = $model;
            $project->trigger(Products::EVENT_USER_NEW_PROJECT);

            //-----------------------------------------------------------------

            //-----------------------------------------------------------------

            $userEvent = new Userevent();
            $userEvent->setLog(Yii::$app->user->id,'user','Uploaded new project ID: #'.$model->id,'1');

            //-----------------------------------------------------------------

            return $this->redirect(['/profile/updateproject', 'id' => $model->id]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $id = Yii::$app->request->post('id');

        if (Products::getProjectSelling($id))
        {
            $this->setProdDel($id);
            return json_encode('The product has buyers. Complete removal is impossible! I turn it off.');
        } else {
            return json_encode($this->deleteFileProject($id));
        }
    }

    private function setProdDel($id)
    {
        $project = $this->findModelNP($id);
        $project->deleted = 1;
        return $project->save();
    }

    private function deleteFileProject($id)
    {
        //Перманентное удаление проекта с чисткой файлов
        $project = $this->findModelNP($id);
        $removeres = json_decode($project->photos);

        if (file_exists($project->project_path)) $this->delTree($project->project_path); //удаляю папку проекта с всем содержимым

        foreach ($removeres as $res)
        {

            if (file_exists($res->filepath.$res->filename)) {

                unlink($res->filepath.$res->filename);
                unlink($res->filepath.'100_100_'.$res->filename);
                unlink($res->filepath.'200_200_'.$res->filename);
                unlink($res->filepath.'400_400_'.$res->filename);
            } // Чистим все картинки предпросмотра
        }

         $project->delete(); //Удаляем проект из базы


        //-----------------------------------------------------------------

        $userEvent = new Userevent();
        $userEvent->setLog(Yii::$app->user->id,'user','Deleted project ID:'.$id,'1');

        //-----------------------------------------------------------------

        return json_encode('Project deleted!');
    }

    //Удаление директории проекта с файлами в ней
    private function delTree($dir)
    {

        FileHelper::removeDirectory($dir);

/*
        if ($objs = glob($dir."/*"))
        {
            foreach($objs as $obj)
            {
                is_dir($obj) ? $this->delTree($obj) : unlink($obj);
            }
        }
        rmdir($dir);*/
    }

    protected function findModel($id)
    {
        if (($model = Products::find()->where(['id' => $id, ])->andWhere(['state' => 1])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //Поиск модели без учета статуса
    protected function findModelNP($id)
    {
        if (($model = Products::find()->where(['id' => $id, ])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getUserFolder()
    {
        return $userfolder = Yii::getAlias('@app').'/projects/user_'.yii::$app->user->id; //Лепим папку пользоваетеля
    }

    public function translite($text)
    {
        $translit = new Translit();
        return $text = strtolower($translit->translit($text, true, 'ru-en'));
    }

    private function addHits($id)
    {
        $model = $this->findModel($id);
        $model->hits++;
        $model->save(false);

    }

    private function checkReate($pid)
    {
        $rating = new Ratings();
        return ($rating::find()->where(['user_id' => yii::$app->user->id, 'project_id' => $pid])->all()) ? false : true; //Проверка голосовал ли текущий пользователь за материал
    }


}
