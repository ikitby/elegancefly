<?php

namespace app\widgets;
use app\models\Products;
use yii\base\Widget;
use app\models\User;
use Yii;

class ProjectsWidget extends Widget{

    public $tpl;
    protected $data;
    public $category;
    public $order;
    public $limit;
    public $state;

    public function init(){
        parent::init();
        if ($this->category === null) {$this->category = '1';}
        if ($this->tpl === null) {$this->tpl = 'gallery';}
        if ($this->limit === null) {$this->limit = '5';}
        if ($this->state === null) {$this->state = '1';}
        $this->tpl .='.php';
        if ($this->order === null) {$this->order = ['sales' => SORT_DESC];}
    }

    public function run() {
        //get cache
        //$users = Yii::$app->cache->get('usergallery');
        //if ($users) return $users;
        $date = new \DateTime('now - 1 month', new \DateTimeZone('UTC')); //Получаем новый объект даты относительно сегодня + 1 месяц
        $date = $date->format('Y-m-d h:m:s'); //устанавливаем нужный формат

        $this->data = Products::find()
            //->joinWith('userLevel')
           // ->where(['auth_assignment.item_name' => $this->usertype])
            //->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->with('catprod')
            ->where(['state' => $this->state, 'category' => $this->category ])
            ->andWhere(['>', 'created_at', $date])
            ->orderBy($this->order)
            ->limit($this->limit)
            ->all();

        $projects = $this->getHtmlMenu($this->data);

        //set cache
        //Yii::$app->cache->set('usergallery' , $users, 60*5);
        return $projects;
    }

    protected function getHtmlMenu($projects) {
        $res = '';
        foreach ($projects as $project) {
            $res .= $this->getTemplate($project);
        }
        return $res;
    }

    protected function getTemplate($project){
        ob_start();
        include __DIR__ . '/projects_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}