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
        if (!Yii::$app->getUser()->isGuest) {

            $project_id = Yii::$app->request->get('id');
            $payedprod = Transaction::allowDownload(Yii::$app->user->id, $project_id); //Проверяем есть ли у зарегистрированного пользователя файл в купленых

            if ($payedprod) {
                $file = Products::findOne($project_id);
                $storagePath = Yii::getAlias( $file->project_path);

                $filename = $file->file;
                $dfile = Yii::$app->response->SendFile($storagePath.$filename, $filename, ['MIME' => 'application/zip', 'inline' => false] );
                $dfile->send();

                } else {
                return json_encode("You can`t perform this action");
            }
        }
    }
    
}