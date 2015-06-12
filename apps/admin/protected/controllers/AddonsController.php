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
**********AddonsController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-5**********
*/
class AddonsController extends BackendBaseController
{
 	/**
     * 插件列表
     */
    public function actionIndex(){
        $list       =   Addons::model()->getList();
        $total      =   $list? count($list) : 1 ;
        $this->render('index',array('_list'=>$list,'pages'=>$this->pages));
    }
    
    
     //创建
    public function actionCreate(){
        if(!is_writable(C('PLUGINS_PATH')))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = Hooks::model()->findAll(
            array(
                'select'=>'name,description',
            )
        ); 
        $hooks=findall_to_array($hooks);
        $this->render('create',array('hooks'=>$hooks));
    }
    
 //预览
    public function actionPreview($output = true){
        $data                   =   $_POST;
        $data['info']['status'] =   (int)$data['info']['status'];
        $extend                 =   array();
        $custom_config          =   trim($data['custom_config']);
        if(isset($data['has_config']) && $custom_config){
            $custom_config = <<<str


        public \$custom_config ='{$custom_config}';
str;
            $extend[] = $custom_config;
        }

        $admin_list = trim($data['admin_list']);
        if(isset($data['has_adminlist']) && $admin_list){
            $admin_list = <<<str


        public \$admin_list = array(
            {$admin_list}
        );
str;
           $extend[] = $admin_list;
        }

        $custom_adminlist = trim($data['custom_adminlist']);
        if(isset($data['has_adminlist']) && $custom_adminlist){
            $custom_adminlist = <<<str


        public \$custom_adminlist = '{$custom_adminlist}';
str;
            $extend[] = $custom_adminlist;
        }

        $extend = implode('', $extend);
        $hook = '';
        $data['hook']=isset($data['hook'])?$data['hook']:array();
        foreach ($data['hook'] as $value) {
            $hook .= <<<str
        //实现的{$value}钩子方法
        public function {$value}(\$param){

        }

str;
        }

        $tpl = <<<str
<?php


/**
 * {$data['info']['title']}插件
 * @author {$data['info']['author']}
 */

    class {$data['info']['name']} extends BasePlugin{

        public \$info = array(
            'name'=>'{$data['info']['name']}',
            'title'=>'{$data['info']['title']}',
            'description'=>'{$data['info']['description']}',
            'status'=>{$data['info']['status']},
            'author'=>'{$data['info']['author']}',
            'version'=>'{$data['info']['version']}'
        );{$extend}

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

{$hook}
    }
str;
        if($output)
            exit($tpl);
        else
            return $tpl;
    }
    
    
  //写插件目录  
    public function actionBuild(){
        $data                   =   $_POST;
        $data['info']['name']   =   trim($data['info']['name']);
        $addonFile              =   $this->actionPreview(false);
        $addons_dir             =   C('PLUGINS_PATH');
        //创建目录结构
        $files          =   array();
        $addon_dir      =   "$addons_dir{$data['info']['name']}/";
        $files[]        =   $addon_dir;
        $addon_name     =   "{$data['info']['name']}.php";
        $files[]        =   "{$addon_dir}{$addon_name}";
        if(isset($data['has_config'])&&$data['has_config'] == 1);//如果有配置文件
            $files[]    =   $addon_dir.'config.php';

      
        $custom_config  =   trim($data['custom_config']);
        if($custom_config)
            $data[]     =   "{$addon_dir}{$custom_config}";

        $custom_adminlist = trim($data['custom_adminlist']);
        if($custom_adminlist)
            $data[]     =   "{$addon_dir}{$custom_adminlist}";

        create_dir_or_files($files);

        //写文件
        file_put_contents("{$addon_dir}{$addon_name}", $addonFile);
        

        if(isset($data['has_config'])&&$data['has_config'] == 1)
            file_put_contents("{$addon_dir}config.php", $data['config']);

        $this->ajaxReturn('创建成功',url('Addons/index'),1);
    }
    
    
     public function actionCheckForm(){
        $data                   =   $_POST;
        $data['info']['name']   =   trim($data['info']['name']);
        if(!$data['info']['name'])
            $this->ajaxReturn('插件标识必须');
        //检测插件名是否合法
        $addons_dir             =   C('PLUGINS_PATH');
        if(file_exists("{$addons_dir}{$data['info']['name']}")){
            $this->ajaxReturn('插件已经存在了');
        }
        $this->ajaxReturn('可以创建','',1);
    }
    

    /**
     * 设置插件页面
     */
    public function actionConfig(){
        $id     =   (int)gp('id');
        $addon  = Addons::model()->findByPk($id);
        $addon=findall_to_array($addon);
        if(!$addon)
            $this->error('插件未安装');
        $addon_class = get_addon_class($addon['name']);

        if(!file_exists(C('PLUGINS_PATH')."/{$addon['name']}/{$addon['name']}.php"))
        {
           $this->error("插件{$addon['name']}文件不存在");
        }
        Yii::import("application.plugins.{$addon['name']}.{$addon['name']}");
        if(!class_exists($addon_class))
            $this->error("插件{$addon['name']}无法实例化,");
        $data  =   new $addon_class();

        $addon['addon_path'] = $data->addon_path;
        $addon['custom_config'] = $data->custom_config;
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;
       
        if($db_config){
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if($value['type'] != 'group'){
                    $addon['config'][$key]['value'] = $db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $custom_config="";
        if($addon['custom_config'])
            $custom_config="";
        $this->render('config',array('data'=>$addon,'custom_config'=>$custom_config));
    }
    
 /**
     * 保存插件设置
     */
    public function actionSaveConfig(){
        $id     =   (int)gp('id');
        $data['config'] =   json_encode(gp('config'));
        $flag = Addons::model()->updateByPk($id, $data);
        if($flag !== false){
           $this->ajaxReturn('保存成功', app()->request->urlReferrer);
        }else{
            $this->ajaxReturn('保存失败');
        }
    }
    
    
    
     /**
     * 启用插件
     */
    public function actionEnable(){
        app()->cache->set('hooks', null);
        $this->actionSetStatus(RESUME_VAL);
    }

    /**
     * 禁用插件
     */
    public function actionDisable(){
        app()->cache->set('hooks', null);
        $this->actionSetStatus(FORBID_VAL);
    }
    
    
 /**
     * 安装插件
     */
    public function actionInstall(){
        $addon_name     =   trim(get('addon_name'));
        $class          =   get_addon_class($addon_name);
        if(!file_exists(C('PLUGINS_PATH')."/{$addon_name}/{$addon_name}.php"))
        {
           $this->ajaxReturn("插件{$addon_name}文件不存在");
        }
        Yii::import("application.plugins.{$addon_name}.{$addon_name}");
        if(!class_exists($class))
            $this->ajaxReturn('插件不存在');
        $addons  =   new $class;
        $info = $addons->info;
        if($info['name']!=$addon_name)
        {
            $this->ajaxReturn('插件信息name值与插件名不一致');
        }
        
        
        if(!$info || !$addons->checkInfo())//检测信息的正确性
            $this->ajaxReturn('插件信息缺失');
        setSession('addons_install_error',null);
        $install_flag   =   $addons->install();
        if(!$install_flag){
            $this->ajaxReturn('执行插件预安装操作失败'.getSession('addons_install_error'));
        }
        $addonsModel    =  new Addons();
        $data           =   $info;
        
        //添加是否安装过判断
        if($addonsModel->exists("name='{$addon_name}'"))
        {
            $this->ajaxReturn('插件已安装');
        }
        
        if(is_array($addons->admin_list) && $addons->admin_list !== array()){
            $data['has_adminlist'] = 1;
        }else{
            $data['has_adminlist'] = 0;
        }
        $addonsModel->attributes=$data;
        if(!$addonsModel->validate())
            $this->ajaxReturn($addonsModel->getMyError());
            
            
        if($addonsModel->save()){
            $config         =   array('config'=>json_encode($addons->getConfig()));
            $addonsModel->updateAll(
                   $config,
                   "name='".$addon_name."'"
            );
           
            $hooks_update   = Hooks::model()->updateHooks($addon_name);
            if($hooks_update){
                app()->cache->set('hooks', null);
                $this->ajaxReturn('安装成功',app()->request->urlReferrer);
            }else{
                //$addonsModel->where("name='{$addon_name}'")->delete();
                $this->ajaxReturn('更新钩子处插件失败,请卸载后尝试重新安装----'.Hooks::model()->getMyError());
            }

        }else{
            $this->ajaxReturn('写入插件数据失败');
        }
    }
    
    
 /**
     * 卸载插件
     */
    public function actionUninstall(){
        $addonsModel    = new Addons();
        $id             =   trim(get('id'));
        $db_addons      =   $addonsModel->findByPk($id);
        $class          =   get_addon_class($db_addons['name']);
        if(!file_exists(C('PLUGINS_PATH')."/{$db_addons['name']}/{$db_addons['name']}.php"))
        {
           $this->ajaxReturn("插件{$db_addons['name']}文件不存在");
        }
        Yii::import("application.plugins.{$db_addons['name']}.{$db_addons['name']}");
        if(!$db_addons || !class_exists($class))
            $this->ajaxReturn('插件不存在');
        setSession('addons_uninstall_error',null);
        $addons =   new $class;
        $uninstall_flag =   $addons->uninstall();
        if(!$uninstall_flag)
            $this->ajaxReturn('执行插件预卸载操作失败'.getSession('addons_uninstall_error'));
        $hooks_update   = Hooks::model()->removeHooks($db_addons['name']);
        if($hooks_update === false){
            $this->ajaxReturn('卸载插件所挂载的钩子数据失败');
        }
        
        app()->cache->set('hooks', null);
        $delete = $addonsModel->deleteAll("name='{$db_addons['name']}'");
        if($delete === false){
            $this->ajaxReturn('卸载插件失败');
        }else{
            $this->ajaxReturn('卸载成功',app()->request->urlReferrer);
        }
    }
    
 	/**
     * 钩子列表
     */
    public function actionHooks(){
        $criteria=new CDbCriteria();
        $criteria->order='id desc';
        $list   =   $this->lists("Hooks",$criteria);
        int_to_string($list, array('type'=>C('HOOKS_TYPE')));
        $this->render('hooks',array('list'=>$list,'pages'=>$this->pages));
    }
    //钩子出编辑挂载插件页面
    public function actionEdit($id){
        $hook = Hooks::model()->findByPk($id);
        $this->render('edithook',array('data'=>$hook));
    }
    //超级管理员删除钩子
    public function actionDel($id){
        if( Hooks::model()->deleteByPk($id) !== false){
            $this->ajaxReturn('删除成功');
        }else{
            $this->ajaxReturn('删除失败');
        }
    }
     public function actionAddhook(){
        $this->render('edithook',array('data'=>null));
    }
     public function actionUpdateHook(){
        $hookModel  =  new Hooks();
        $data=$_POST;
        if($data){
            if($data['id']){
                $flag = $hookModel->updateByPk($data['id'], $data);
                if($flag !== false)
                    $this->ajaxReturn('更新成功',app()->request->urlReferrer);
                else
                    $this->ajaxReturn('更新失败');
            }else{
                $hookModel->attributes=$data;
                if($hookModel->validate())
                {
                    if($hookModel->save()){
                        $this->ajaxReturn('新增成功', app()->request->urlReferrer);
                    } else {
                        $this->ajaxReturn('新增失败');
                    }
                }else {
                    $this->ajaxReturn($this->get_model_ajax_error($hookModel));
                }
               
            }
        }else{
            $this->ajaxReturn('参数错误');
        }
    }
}