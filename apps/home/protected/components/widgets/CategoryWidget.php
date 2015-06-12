<?php
/*
                               _oo0oo_
                              o8888888o
                              88" . "88
                              (| -_- |)
                              0\  =  /0
                            ___/`---'\___
                          .' \\|     |// '.
                         / \\|||  :  |||// \
                        / _||||| -:- |||||- \
                       |   | \\\  -  /// |   |
                       | \_|  ''\---/''  |_/ |
                       \  .-\___ '-' ___/-.  /
                   ____`. .'   /--.--\  `. .'____
                   ."" '< `.___\_<|>_/___.' >' "".
                  | | : `- \`.; \ _ /`;.`/ - ` : | |
                  \ \`_.   \_ ___\ / ___ _/  .-` / /
             =====`-.____`.____\_____/____.-`____.-`=====
                               '=---='
*/
/*
**********CategoryWidget.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-16**********
*/
class CategoryWidget extends CWidget
{
    public $cate,$child,$category;
    public function init()
    {
        $field = 'id,name,pid,title,link_id';
        if($this->child){
			$this->category = Category::model()->getTree($this->cate, $field);
			$this->category = $this->category['_'];
		} else {
			$this->category =Category::model()->getSameLevel($this->cate, $field);
		}
		
    }
    public function run()
    {
        $this->render('category_lists',array('category'=>$this->category,'current'=>$this->cate));
    }
}