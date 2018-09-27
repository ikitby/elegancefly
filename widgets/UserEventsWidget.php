<?php

namespace app\widgets;
use app\models\Userevent;
use yii\base\Widget;
use app\models\User;
use Yii;

class UserEventsWidget extends Widget{

    public $etype;
    public $events;
    public $useid;
    public $count;
    public $tpl;
    public $eprogress;

    public function init(){
        parent::init();
        if ($this->eprogress === null) {$this->eprogress = [0,1];}
        if ($this->etype === null) {$this->etype = 'user';}
        if ($this->tpl === null) {$this->tpl = 'eventslist';}
        if ($this->count === null) {$this->count = 5;}
        $this->tpl .='.php';
    }

    public function run() {
        //get cache
        //$users = Yii::$app->cache->get('usergallery');
        //if ($users) return $users;

        $this->events = Userevent::find()
            ->with('eventUser')
            ->where(['event_type' => $this->etype])
            ->andWhere(['event_progress' => $this->eprogress])
            //->joinWith('userLevel')
            //->where(['auth_assignment.item_name' => 'Painter'])
            //->orWhere(['auth_assignment.item_name' => 'Creator'])
            //->andWhere(['status' => '10'])
            ->orderBy(['event_time' => SORT_DESC])
            ->limit($this->count)
            ->all();

        $events = $this->getHtmlMenu($this->events);

        //set cache
        //Yii::$app->cache->set('usergallery' , $users, 60*5);
        return $events;
    }

    protected function getHtmlMenu($events) {
        $res = '';
        foreach ($events as $event) {
            $res .= $this->getTemplate($event);
        }
        return $res;
    }

    protected function getTemplate($event){
        ob_start();
        include __DIR__ . '/events_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}