<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 07.06.2018
 * Time: 15:26
 */
namespace app\controllers;

use app\models\Products;
use Yii;
use app\models\Transaction;

use yii\helpers\Json;

class DownloadController extends AppController
{

    public function actionProject()
    {
        if (!Yii::$app->getUser()->isGuest/* && Yii::$app->request->isAjax*/) {

            $project_id = Yii::$app->request->get('id');
            $payedprod = Transaction::allowDownload(Yii::$app->user->id, $project_id); //Проверяем есть ли у зарегистрированного пользователя файл в купленых

            if ($payedprod) {

                $file = Products::findOne($project_id);
                $storagePath = Yii::getAlias('@app/web/'.$file->project_path);
                $filename = $file->file;
                $dfile = Yii::$app->response->SendFile($storagePath.$filename, $filename, ['MIME' => 'application/zip', 'inline' => false] );
                //$dfile->setDownloadHeaders($filename, 'application/zip');
                $dfile->send();

                //return json_encode("ok!");
                } else {
                return json_encode("Вы не покупали данный товар");
            }
        }

        //return json_encode($project_id);
    }


}