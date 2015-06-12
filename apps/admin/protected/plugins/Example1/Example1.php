<?php


/**
 * 示列1插件
 * @author 无名
 */

    class Example1 extends BasePlugin{

        public $info = array(
            'name'=>'Example1',
            'title'=>'示列1',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.11'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function AdminIndex($param){

        }

    }