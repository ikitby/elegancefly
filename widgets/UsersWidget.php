<?php

namespace app\widgets;
use yii\base\Widget;
use app\models\User;
use Yii;

class UsersWidget extends Widget{

    public $tpl;
    public $data;
    public $usertype;

    public function init(){
        parent::init();
        if ($this->usertype === null) {$this->usertype = 'painter';}
        if ($this->tpl === null) {$this->tpl = 'gallery';}
        $this->tpl .='.php';
    }

    public function run() {
        //get cache
        //$menu = Yii::$app->cache->get('usergallery');
        //if ($menu) return $menu;

        $this->data = User::find()->where(['usertype' => 'painter'])->limit(10)->all();
        $menu = $this->getHtmlMenu($this->data);

        //set cache
        //Yii::$app->cache->set('usergallery' , $menu, 60*60*24 );
        return $menu;
    }

    protected function getHtmlMenu($data) {
        $res = '';
        foreach ($data as $user) {
            $res .= $this->getTemplate($user);
        }
        return $res;
    }

    protected function getTemplate($user){
        ob_start();
        include __DIR__ . '/users_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}