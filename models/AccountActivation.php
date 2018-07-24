<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 22.07.2018
 * Time: 15:27
 */

namespace app\models;

use yii\base\InvalidParamException;
use yii\base\Model;

use Yii;


class AccountActivation extends Model
{

    /* @var $user \$app\models\User  */

    private $_user;

    public function __construct($key, $config = [])
    {
        if (empty($key) || !is_string($key))
            throw new InvalidParamException('Ключ не может быть пустым');

            $this->_user = User::findByActivateKey($key);

        if (!$this->_user) {
            Yii::$app->session->setFlash('error', 'Ошибка активации');
            //throw new InvalidParamException( "Ключ не подходит");
        }
        parent::__construct($config);
    }

    public function activateAccount()
    {
        if ($this->_user) {
            $user = $this->_user;
            $user->status = User::STATUS_ACTIVE;
            $user->removePasswordResetToken();
            return $user->save();
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка активации');
            return false;
        }
    }

    /**
     * @return \
     */
    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }


}