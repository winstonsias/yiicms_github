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
**********WPlugin.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
/**
 * 插件机制 Component
 */
class WPlugin extends CApplicationComponent {

        public $pluginDir = '';
        private $_listeners = array();

        /**
         * 初始化
         */
        public function init() {
                parent::init();
                $plugins = $this->getActivePlugs();
                if ($plugins && is_array($plugins)) {
                        foreach ($plugins as $plugin) {
                                $path = $this->pluginDir . $plugin['directory'] . '/' . ucfirst($plugin['directory']) . '.php';
                                if (file_exists($path)) {
                                        require_once ($path);
                                        $class = ucfirst($plugin['directory']);
                                        if (class_exists($class)) {
                                                new $class($this);
                                        }
                                }
                        }
                }
        }

        /**
         *  注册hook
         * @param string $hook
         * @param object $reference
         * @param string $method
         */
        public function register (&$reference, $method) {
                $hook=get_class($reference).'.'.$method;
                $key = get_class($reference) . '->' . $method;
                $this->_listeners[$hook][$key] = array(&$reference, $method);
                
        }

        /**
         * 执行 hook
         * @param string $hook
         */
        public function trigger($hook) {
                if ($this->checkHookExist($hook)) {
                        foreach ($this->_listeners[$hook] as $listener) {
                                $class = $listener[0];
                                $method = $listener[1];
                                if (method_exists($class, $method)) {
                                        $args = array_slice(func_get_args(), 1);
                                        call_user_func_array(array($class, $method), $args);
                                }
                        }
                }
        }

        /**
         * 检查hook是否存在
         * @param string $hook
         * @return boolean
         */
        public function checkHookExist($hook) {
                if (isset($this->_listeners[$hook]) && is_array($this->_listeners[$hook]) && count($this->_listeners[$hook])) {
                        return TRUE;
                }
                return false;
        }

        /**
         * 获取激活的插件  //需要修改为动态获取
         * @return array
         */
        public function getActivePlugs() {
                $arr = array(
                    'SiteStat' => array(
                        'name' => 'SiteStats',
                        'directory' => 'SiteStat'
                    ),
                    'SystemInfo' => array(
                        'name' => 'SystemInfo',
                        'directory' => 'SystemInfo'
                    ),
                    'EditorForAdmin' => array(
                        'name' => 'EditorForAdmin',
                        'directory' => 'EditorForAdmin'
                    ),
                    'Editor' => array(
                        'name' => 'Editor',
                        'directory' => 'Editor'
                    ),
                );
                return $arr;
        }

       

}