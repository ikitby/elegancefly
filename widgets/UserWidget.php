<?php

namespace app\widgets;
use yii\base\Widget;
use app\models\User;
use Yii;

class UserWidget extends Widget
{
    public $tpl;
    public $user;
    public $usertype;

    public function init(){
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'User';
        }
        $this->tpl .='.php';
    }

    public function run() {
        //get cache
        //$menu = Yii::$app->cache->get('blogmenu');
        //if ($menu) return $menu;

        $this->user = User::find()->where(['id' => Yii::$app->user->id])->one();
        $user = $this->getHtmlUser($this->user);

        //set cache
       //Yii::$app->cache->set('blogmenu' , $menu, 60*60*24 );
        return $user;
    }

    protected function getHtmlUser($user) {
        $res = '';
        $res = $this->getTemplate($user);
        return $res;
    }

    protected function getTemplate($user){
        ob_start();
        include __DIR__ . '/user_tpl/' . $this->tpl;
        return ob_get_clean();
    }

}