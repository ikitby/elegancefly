<?php

namespace app\controllers;

use app\models\Ratings;
use app\models\Tags;
use app\models\Themsprod;
use app\models\UploadProject;
use Codeception\Lib\Di;
use Codeception\Lib\Generator\Helper;
use Yii;
use app\models\Products;
use app\models\ProductsSearch;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

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

        $products = Products::find()->where(['state' => 1, 'deleted' => 0]);
        $productsall = $products;

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
                ])
            //->where(['deleted' => 0])
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
        $tags = ArrayHelper::getColumn($temsprod->products, 'id'); //получаем список id продуктов с неободимым тегом

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
        $rating = new Ratings();
        $request = Yii::$app->request;

        $pid = $request->get('pid');
        $rate = $request->get('rating');
        if (Yii::$app->request->isAjax && Yii::$app->request->post('pid') && Yii::$app->request->post('rating')) {
            if ($rating::find()->where(['user_id' => yii::$app->user->id, 'project_id' => $pid])->all()) {
                return false;
            }
            $rating->setRating($pid, $rate);
        }
        return true;
    }

    public function actionView($id)
    {

        $this->addHits($id);
        $model = $this->findModel($id);

        $rating_count = $model->getRatings()->select('rating')->count();
        $rating = $model->getRatings()->select('rating')->asArray()->all();

        $model->rating = $model->afterFind();

        //$rateUsers = $model->rateUsers; //Так можно получить оценивших материал пользователей
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
        $filemodel = new UploadProject();
        $model->user_id = yii::$app->user->id;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'photos'); //цепляем из нашей модельки файл по его полю

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

            return $this->redirect(['update', 'id' => $model->id]);
        }
/*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $themes = Yii::$app->request->post('Products');
            $themes = $themes['themes'];
            $model->saveThems($themes);
            return $this->redirect(['view', 'id' => $model->id]);
        }
*/
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
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
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getUserFolder()
    {
        return $userfolder = 'projects/user_'.yii::$app->user->id; //Лепим папку пользоваетеля
    }

    public function translite($text)
    {
        $translit = new Translit();
        return $text = strtolower($translit->translit($text, true, 'ru-en'));
    }

    public function actionDownloadFile()
    {
        $model = $this->findModel($id);
        //отключить профайлеры
        $this->disableProfilers();
        $file = '/path/to/file/some_file.txt';
        // отдаем файл
        Yii::app()->request->sendFile(basename($file),file_get_contents($file));
    }

    private function addHits($id)
    {
        $model = $this->findModel($id);
        $model->hits++;
        $model->save(false);
    }



}
