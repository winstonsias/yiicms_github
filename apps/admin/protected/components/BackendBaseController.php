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
**********BackendBaseController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class BackendBaseController extends LjhController
{
    protected $config,$adminmenu;//全局配置，按钮列表
    public function beforeAction()
    {
        $this->_getModuleName();
     	$this->_initialize();
        return true;
    }
    private function _getModuleName()
    {
        $path=dirname(__FILE__);
        $modulepath=str_replace(DS.'protected'.DS.'components', '', $path);
        $patharr=explode(DS, $modulepath);
        $modulename=$patharr[count($patharr)-1];//获取当前module名
        defined('MODULE_NAME') or define('MODULE_NAME', $modulename);
    }
     /**
     * 后台控制器初始化
     */
    protected function _initialize(){
        // 获取当前用户ID
        define('UID',is_login());
        if( !UID ){// 还没登录 跳转到登录页面
            $this->redirect(array('public/login'));
        }
    	/* 读取数据库中的配置 */
        $this->config	= app()->cache->get('DB_CONFIG_DATA');
        if(!$this->config){
            $config=new Config();
            $this->config=app()->cache->set('DB_CONFIG_DATA',$config->lists()); 
        }

        // 是否是超级管理员
        define('IS_ROOT',   is_administrator());
        if(!IS_ROOT && C('ADMIN_ALLOW_IP')){
            // 检查IP地址访问
            if(!in_array(Yii::app()->request->userHostAddress,explode(',',C('ADMIN_ALLOW_IP')))){
                $this->error('403:禁止访问');
            }
        }
        // 检测访问权限
        $access =   $this->accessControl();
        if ( $access === false ) {
            $this->error('403:禁止访问');
        }elseif( $access === null ){
            $dynamic        =   $this->checkDynamic();//检测分类栏目有关的各项动态权限
            if( $dynamic === null ){
                //检测非动态权限
                $rule  = strtolower(MODULE_NAME.'/'.$this->getId().'/'.$this->getAction()->getId());
                if ( !$this->checkRule($rule,array('in','1,2')) ){
                    $this->error('未授权访问!');
                }
            }elseif( $dynamic === false ){
                $this->error('未授权访问!');
            }
        }
        $this->adminmenu=$this->getMenus();
    }
    
	/**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     */
    final protected function accessControl(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
		$allow = C('ALLOW_VISIT');
		$deny  = C('DENY_VISIT');
		$check = strtolower($this->getId().'/'.$this->getAction()->getId());
        if ( !empty($deny)  && in_array_case($check,$deny) ) {
            return false;//非超管禁止访问deny中的方法
        }
        if ( !empty($allow) && in_array_case($check,$allow) ) {
            return true;
        }
        return null;//需要检测节点权限
    }
	/**
     * 检测是否是需要动态判断的权限
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     *
     */
    protected function checkDynamic(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        return null;//不明,需checkRule
    }
/**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type=AuthRule::RULE_URL, $mode='url'){
       if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }
    
/**
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     */
    final public function getMenus($controller=''){
        $controller=$controller?$controller:$this->getId();
        $menus="";
        // $menus  =   session('ADMIN_MENU_LIST'.$controller);
        if(empty($menus)){
            $criteria=new CDbCriteria();
            $criteria->condition='pid=0 and hide=0';
            // 获取主菜单

            if(!C('DEVELOP_MODE')){ // 是否开发者模式
                $criteria->addCondition('is_dev=0');
            }
            $criteria->order='sort asc';

            $menus['main']  =   findall_to_array(Menu::model()->findAll($criteria));
            $menus['child'] = array(); //设置子节点

            //高亮主菜单
            $criteria=new CDbCriteria();
            $criteria->condition="url like '%{$controller}/".$this->getAction()->getId()."%'";
            $criteria->select='id';
            $current = Menu::model()->find($criteria);
            if($current){
                //$menu=new Menu();
                $nav = Menu::model()->getPath($current['id']);
                $nav_first_title = $nav[0]['title'];

                foreach ($menus['main'] as $key => $item) {
                    if (!is_array($item) || empty($item['title']) || empty($item['url']) ) {
                        $this->error('控制器基类$menus属性元素配置有误');
                    }
                    if( stripos($item['url'],MODULE_NAME)!==0 ){
                        $item['url'] = MODULE_NAME.'/'.$item['url'];
                    }
                    // 判断主菜单权限
                    if ( !IS_ROOT && !$this->checkRule($item['url'],AuthRule::RULE_MAIN,null) ) {
                        unset($menus['main'][$key]);
                        continue;//继续循环
                    }
                    $menus['main'][$key]['class']='';
                    // 获取当前主菜单的子菜单项
                    if($item['title'] == $nav_first_title){
                        $menus['main'][$key]['class']='current';
                        //生成child树
                         $criteria=new CDbCriteria();
                        $criteria->condition="pid=:pid";
                        $criteria->params=array(':pid'=>$item['id']);
                        $criteria->select='`group`';
                        $criteria->distinct=true;
                        $groups = findall_to_array(Menu::model()->findAll($criteria));
                        if($groups){
                            $groups = array_filter(array_column($groups, 'group'));
                        }else{
                            $groups =   array();
                        }
                        //获取二级分类的合法url
                      
                        $criteria=new CDbCriteria();
                        $criteria->condition='pid=:pid and hide=0';
                        $criteria->params=array(':pid'=>$item['id']);
                        // 获取主菜单
            
                        if(!C('DEVELOP_MODE')){ // 是否开发者模式
                            $criteria->addCondition('is_dev=0');
                        }
                        $criteria->select='id,url';
                        //$menu=new Menu();
                        $second_urls = Menu::model()->findAll($criteria);
                        if(!IS_ROOT){
                            // 检测菜单权限
                            $to_check_urls = array();
                            foreach ($second_urls as $key=>$to_check_url) {
                                if( stripos($to_check_url,MODULE_NAME)!==0 ){
                                    $rule = MODULE_NAME.'/'.$to_check_url;
                                }else{
                                    $rule = $to_check_url;
                                }
                                if($this->checkRule($rule, AuthRule::RULE_URL,null))
                                    $to_check_urls[] = $to_check_url;
                            }
                        }
                         $criteria=new CDbCriteria();
                         
                        // 按照分组生成子菜单树
                        foreach ($groups as $g) {
                        
                            if(isset($to_check_urls)){
                                if(empty($to_check_urls)){
                                    // 没有任何权限
                                    continue;
                                }else{
                                    $criteria->addInCondition('url', $to_check_urls);
                                }
                            }
                            $criteria->addCondition('pid=:pid and hide=0');
                            $criteria->params=array(':pid'=>$item['id']);
                            if(!C('DEVELOP_MODE')){ // 是否开发者模式
                                 $criteria->addCondition('is_dev=0');
                            }
                            $criteria->addCondition('`group`=:group');
                            $criteria->params[':group']=$g;
                            $criteria->select='id,pid,title,url,tip';
                            $criteria->order='sort asc';
                            $menuList = findall_to_array(Menu::model()->findAll($criteria));
                            $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
                        }
                        if($menus['child'] === array()){
                            //$this->error('主菜单下缺少子菜单，请去系统=》后台菜单管理里添加');
                        }
                    }
                }
            }
            // session('ADMIN_MENU_LIST'.$controller,$menus);
        }
        return $menus;
    }
    
    
 	/**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     */
    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = Menu::model()->findAll(
                array(
                    'select'=>'id,pid,title,url,tip,hide',
                    'order'=>'sort asc',
                )
            );

            $list=findall_to_array($list);
            foreach ($list as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $list[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value) {
                if(!empty($value['operator'])){
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        }else{
            $nodes = Menu::model()->findAll(
                array(
                    'select'=>'title,url,tip,pid',
                    'order'=>'sort asc',
                )
            );
            $nodes=findall_to_array($nodes);
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],MODULE_NAME)!==0 ){
                    $nodes[$key]['url'] = MODULE_NAME.'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }
}