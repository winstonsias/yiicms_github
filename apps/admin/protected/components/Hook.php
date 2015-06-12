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
**********Hook.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-5**********
*/
class Hook {

    static private  $tags       =   array();

    /**
     * 动态添加插件到某个标签
     * @param string $tag 标签名称
     * @param mixed $name 插件名称
     * @return void
     */
    static public function add($tag,$name) {
        if(!isset(self::$tags[$tag])){
            self::$tags[$tag]   =   array();
        }
        if(is_array($name)){
            self::$tags[$tag]   =   array_merge(self::$tags[$tag],$name);
        }else{
            self::$tags[$tag][] =   $name;
        }
    }

    /**
     * 批量导入插件
     * @param array $data 插件信息
     * @param boolean $recursive 是否递归合并
     * @return void
     */
    static public function import($data,$recursive=true) {
        if(!$recursive){ // 覆盖导入
            self::$tags   =   array_merge(self::$tags,$data);
        }else{ // 合并导入
            foreach ($data as $tag=>$val){
                if(!isset(self::$tags[$tag]))
                    self::$tags[$tag]   =   array();            
                if(!empty($val['_overlay'])){
                    // 可以针对某个标签指定覆盖模式
                    unset($val['_overlay']);
                    self::$tags[$tag]   =   $val;
                }else{
                    // 合并模式
                    self::$tags[$tag]   =   array_merge(self::$tags[$tag],$val);
                }
            }            
        }
    }

    /**
     * 获取插件信息
     * @param string $tag 插件位置 留空获取全部
     * @return array
     */
    static public function get($tag='') {
        if(empty($tag)){
            // 获取全部的插件信息
            return self::$tags;
        }else{
            return self::$tags[$tag];
        }
    }

    /**
     * 监听标签的插件
     * @param string $tag 标签名称
     * @param mixed $params 传入参数
     * @return void
     */
    static public function listen($tag, &$params=NULL) {

        if(isset(self::$tags[$tag])) {

            foreach (self::$tags[$tag] as $name) {

                $result =   self::exec($name, $tag,$params);
        
                if(false === $result) {
                    // 如果返回false 则中断插件执行
                    return ;
                }
            }

        }
        return;
    }

    /**
     * 执行某个插件
     * @param string $name 插件名称
     * @param string $tag 方法名（标签名）     
     * @param Mixed $params 传入的参数
     * @return void
     */
    static public function exec($name, $tag,&$params=NULL) {
        if(file_exists(Yii::getPathOfAlias("application.plugins.{$name}.{$name}").".php"))
        {
            Yii::import("application.plugins.{$name}.{$name}");
            $class   =  $name;
            if(class_exists($class))
            {
                $addon   = new $class();
                if(method_exists($addon, $tag))
                {
                    return $addon->$tag($params);
                }
            }
        }
    }
}
