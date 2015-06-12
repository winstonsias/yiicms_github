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
**********Addons.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-29**********
*/
class Addons extends LjhModel
{
/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('status, has_adminlist', 'numerical', 'integerOnly'=>true),
			array('name, author', 'length', 'max'=>40),
			array('title, version', 'length', 'max'=>20),
			array('create_time', 'length', 'max'=>10),
			array('description, config', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, description, status, config, author, version, create_time, has_adminlist', 'safe', 'on'=>'search'),
		);
	}
    public function tableName()
    {
        return "{{addons}}";
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
//自动完成
	public function afterValidate()
	{
	    parent::afterValidate();
	    
        $this->create_time=NOW_TIME;
        $this->status=1;
	    
	}
    
 /**
     * 获取插件列表
     * @param string $addon_dir
     */
    public function getList($addon_dir = ''){
        if(!$addon_dir)
            $addon_dir = app()->WPlugin->pluginDir;
        $dirs = array_map('basename',glob($addon_dir.'*', GLOB_ONLYDIR));
        if($dirs === FALSE || !file_exists($addon_dir)){
            $this->error = '插件目录不可读或者不存在';
            return FALSE;
        }
		$addons			=	array();
		$criteria=new CDbCriteria();
		$criteria->addInCondition('name', $dirs);
		$list			=	$this->findAll($criteria);
		$list=findall_to_array($list);
		foreach($list as $addon){
			$addon['uninstall']		=	0;
			$addons[$addon['name']]	=	$addon;
		}

        foreach ($dirs as $value) {
            if(!isset($addons[$value])){
				$class = get_addon_class($value);
				Yii::import("application.plugins.{$class}.{$class}");
				if(!class_exists($class)){ // 实例化插件失败忽略执行
					continue;
				}
                $obj    =   new $class();
				$addons[$value]	= $obj->info;
				if($addons[$value]){
					$addons[$value]['uninstall'] = 1;
					$addons[$value]['status']=-2;
				}
			}
        }
        int_to_string($addons, array('status'=>array(-1=>'损坏', 0=>'禁用', 1=>'启用', -2=>'未安装')));
        $addons = list_sort_by($addons,'uninstall','desc');
        return $addons;
    }
}