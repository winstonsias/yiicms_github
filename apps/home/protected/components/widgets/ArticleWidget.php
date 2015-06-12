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
**********ArticleWidget.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-16**********
*/
class ArticleWidget extends CWidget
{
    public $category,$child,$list,$document;
    public function init()
    {
       $cate=Category::model()->getChildrenId($this->category); 
       $this->document=new Document();
       $this->list=$this->document->lists($cate,'`level` DESC,`id` DESC',1);

    }
    public function run()
    {
        $this->render('article_list',array('lists'=>$this->list,'pages'=>$this->document->pages));
    }
}                               