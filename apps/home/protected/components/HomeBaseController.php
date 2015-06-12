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
**********HomeBaseController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-14**********
*/
class HomeBaseController extends CController
{
    protected $config;//全局配置
    public $pages;//分页对象 
    public $total;//列表总数
    public function beforeAction()
    {
     	$this->_initialize();
        return true;
    }
    protected function _initialize(){
    	/* 读取数据库中的配置 */
        $this->config	= app()->cache->get('DB_CONFIG_DATA');
        if(!$this->config){
            $config=new Config();
            $this->config=app()->cache->set('DB_CONFIG_DATA',$config->lists()); 
        }
    }
}