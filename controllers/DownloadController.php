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

    public function actionIndex()
    {
        $cartprod = $this->getCartItems();

        if (empty($cartprod)) {return $this->redirect(['/catalog']);}

        return $this->render('index', [
            'cartprod'      => $cartprod,
        ]);
    }


}