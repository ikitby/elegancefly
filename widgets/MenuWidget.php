<?php

namespace app\widgets;
use app\models\Products;
use app\models\Catprod;
use yii\base\Widget;
use Yii;

class MenuWidget extends Widget{

    public $tpl;
    public $data;

    public function init(){
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'Menu';
        }
        $this->tpl .='.php';
    }

	public function run() {
        //get cache
        $menu = Yii::$app->cache->get('catmenu');
        if ($menu) return $menu;

        $this->data = Catprod::find()->asArray()->indexBy('id')->all();
        $menu = $this->getHtmlMenu($this->data);

        //set cache
        Yii::$app->cache->set('catmenu' , $menu, 60*60 );
		return $menu;
	}

	protected function getHtmlMenu($data) {
        $res = '<ul class="list-group">';

        foreach ($data as $category) {
            $category{'artcount'} = Products::find()->where(['category' => $category{'id'}, 'state' => 1])->count();
            $res .= $this->getTemplate($category);
        }
        $res .= '</ul>';

        return $res;
    }

    protected function getTemplate($category){
        ob_start();
            include __DIR__ . '/menu_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}