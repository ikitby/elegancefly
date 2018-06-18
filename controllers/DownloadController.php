<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 07.06.2018
 * Time: 15:26
 */
namespace app\controllers;

use Yii;
use app\models\Transaction;

use yii\helpers\Json;

class DownloadController extends AppController
{

    public function actionProject()
    {
        if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
            $project_id = Yii::$app->request->get('id');
            //$cartprod = $this->getCartItems();
            //if (empty($cartprod)) {return $this->redirect(['/catalog']);}
        }

        return json_encode($project_id);
    }


}