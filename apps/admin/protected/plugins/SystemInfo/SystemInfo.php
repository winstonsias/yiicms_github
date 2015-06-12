<?php

/**
 * 系统环境信息插件
 * @author thinkphp
 */

    class SystemInfo extends BasePlugin{

        public $info = array(
            'name'=>'SystemInfo',
            'title'=>'系统环境信息',
            'description'=>'用于显示一些服务器的信息',
            'status'=>1,
            'author'=>'thinkphp',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function AdminIndex($param){
            $config = $this->getConfig();
            $this->render('widget',array('config'=>$config));
            
        }
        
        public function test()
        {
            echo "adsf";
        }
    }