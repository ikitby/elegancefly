<?php

namespace app\controllers;

use app\models\UploadProject;
use Codeception\Lib\Generator\Helper;
use Yii;
use app\models\Products;
use app\models\ProductsSearch;
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
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $this->addHits($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
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
                $model->saveProject($filemodel->uploadZip($file, $userfolder, $projectfolder), $userfolder.'/'.$projectfolder.'/'); //запускаем сохранение файла в базе с именем сохраненного файла
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
        $model->themes = $model->getTems();
        $model->user_id = yii::$app->user->id;
        $projectpath = $model->project_path;

        if ($model->load(Yii::$app->request->post())) //обработка категорий
        {
            $themes = Yii::$app->request->post('Products');
            $themes = $themes['themes'];
            $model->saveThems($themes);

            $model->save();
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
