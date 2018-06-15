<?php

namespace app\controllers;

use app\models\ImageUpload;
use app\models\Transaction;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends AppController
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


    public function actionPayments()
    {
        if (Yii::$app->user->isGuest) {return $this->redirect(['/login']);}
        $id = Yii::$app->user->id;
        $payments = Transaction::find()->where(['action_user' => $id])->orderBy(['id' => SORT_DESC])->all();

        return $this->render('payments', [
            'payments' => $payments,
        ]);
    }


    public function actionEdit()
    {
        $id = Yii::$app->user->id;

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
}
