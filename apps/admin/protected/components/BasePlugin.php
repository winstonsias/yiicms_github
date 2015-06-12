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
**********BasePlugin.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
abstract class BasePlugin extends BackendBaseController
{

    public $addon_path          =   '';
    public $custom_config       =   '';
    public $admin_list          =   array();
    public $custom_adminlist    =   '';

    public $plugin=NULL;
    public $pluginname="";//插件name
    public $pluginpath="";//插件地址
    public $info  =  array();
    public $config_file  = '';//配置文件
    public $layout=false;
    public function __construct($obj=null)
    {
        $this->addon_path   = C('PLUGINS_PATH').$this->pluginname.DS;
        $this->pluginname=get_class($this);
        $this->pluginpath=C('PLUGINS_PATH').$this->pluginname.DS;
        if(is_file($this->pluginpath.'config.php')){
            $this->config_file = $this->pluginpath.'config.php';
        }
        
    }
	/**
     * 获取插件的配置数组
     */
    final public function getConfig($name=''){
        static $_config = array();
        if(empty($name)){
            $name = $this->pluginname;
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $criteria=new CDbCriteria();
        $criteria->condition='name=:name and status=1';
        $criteria->params=array(':name'=>$name);
        $criteria->select='config';
        $addons=new Addons();
        $config  =   $addons->find($criteria);
        $config=findall_to_array($config);
        if($config['config']!='null'){
            $config=$config['config'];
            $config   =   json_decode($config, true);
        }else{
            $temp_arr = include $this->config_file;
            if(is_array($temp_arr))
            {
                foreach ($temp_arr as $key => $value) {
                    if($value['type'] == 'group'){
                        foreach ($value['options'] as $gkey => $gvalue) {
                            foreach ($gvalue['options'] as $ikey => $ivalue) {
                                $config[$ikey] = $ivalue['value'];
                            }
                        }
                    }else{
                        $config[$key] = isset($value['value'])?$value['value']:'';
                    }
                }
            }
        }
        $_config[$name]     =   $config;
        return $config;
    }
    /**
     * 扩展模板路径重载
     * @see CController::getViewFile()
     */
    public function getViewFile($viewName)
    {
		return $this->pluginpath."views".DS.$viewName.'.php';
    }
    
  final public function checkInfo(){
        $info_check_keys = array('name','title','description','status','author','version');
        foreach ($info_check_keys as $value) {
            if(!array_key_exists($value, $this->info))
                return FALSE;
        }
        return TRUE;
    }
    
    abstract function install();
    abstract function uninstall();
}