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
        $users = Yii::$app->cache->get('usergallery');
        if ($users) return $users;

        $this->data = User::find()
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => 'Painter'])
            ->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->andWhere(['status' => '10'])
            ->limit(10)
            ->all();

        $users = $this->getHtmlMenu($this->data);

        //set cache
        Yii::$app->cache->set('usergallery' , $users, 60*5);
        return $users;
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