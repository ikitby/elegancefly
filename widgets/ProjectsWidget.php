<?php

namespace app\widgets;
use yii\base\Widget;
use app\models\User;
use Yii;

class ProjectsWidget extends Widget{

    public $tpl;
    public $data;
    public $category;
    public $order;
    public $limit;
    public $status;

    public function init(){
        parent::init();
        if ($this->usertype === null) {$this->category = ['6'];}
        if ($this->tpl === null) {$this->tpl = 'gallery';}
        if ($this->limit === null) {$this->limit = '5';}
        if ($this->status === null) {$this->status = '10';}
        $this->tpl .='.php';
        if ($this->order === null) {$this->order = ['sales' => SORT_DESC, 'rate' => SORT_DESC, 'name' => SORT_DESC];}
    }

    public function run() {
        //get cache
        //$users = Yii::$app->cache->get('usergallery');
        //if ($users) return $users;

        $this->data = User::find()
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => $this->usertype])
            //->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->andWhere($this->status)
            ->orderBy($this->order)
            ->limit($this->limit)
            ->all();

        $users = $this->getHtmlMenu($this->data);

        //set cache
        //Yii::$app->cache->set('usergallery' , $users, 60*5);
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
        include __DIR__ . '/projects_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}