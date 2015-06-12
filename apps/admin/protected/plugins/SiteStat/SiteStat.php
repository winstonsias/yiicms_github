<?php

/**
 * 系统环境信息插件
 */
class SiteStat extends BasePlugin {



    public $info = array(
        'name'=>'SiteStat',
        'title'=>'站点统计信息',
        'description'=>'统计站点的基础信息',
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
        if($config['display'])
        {
            $info['user']		=	Member::model()->count();
            $info['action']		=	1;
            $info['document']	=	Document::model()->count();
            $info['category']	=	Category::model()->count();
            $info['model']		=	DocModel::model()->count();
           $this->render('info',$info);
        }
    }
}