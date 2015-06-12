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
**********AuthManagerController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-3**********
*/
class AuthManagerController extends BackendBaseController
{
    public function actionIndex()
    {
        $criteria=new CDbCriteria();
        $lists=$this->lists('AuthGroup', $criteria);
        $this->render('index',array('lists'=>$lists,'pages'=>$this->pages));
    }
    
    
 	/**
     * 更改状态    禁用  启用 删除
     * Enter description here ...
     * @param unknown_type $method
     */
    public function actionChangeStatus($method=null)
    {
        $id = array_unique((array)gp('id'));
        $id = is_array($id) ? implode(',', $id) : (array)$id;
        if ( empty($id) ) {
            $this->ajaxReturn('请选择要操作的数据!');
        }

        $condition="id in ($id)";
        $attributes=array();
        $result="异常出错！";
        switch ($method)
        {
            case 'resumeGroup':
                $attributes['status']=RESUME_VAL;
                break;
            case 'forbidGroup':
                $attributes['status']=FORBID_VAL;
                break;
            case 'deleteGroup':
                $attributes['status']=DELETE_VAL;
                break;
        }
        
        if(!empty($attributes))
        {
           $result= $this->process_model('AuthGroup',$attributes,$condition);
        }

        $this->ajaxReturn($result,url('AuthManager/index'));
    }
    
    public function actionCreateGroup()
    {
        $this->render('add');
    }
    public function actionEditGroup()
    {
        $id=get('id');
        $auth_group=AuthGroup::model()->findByPk($id);
        $this->render('add',array('auth_group'=>$auth_group));
    }
    //更新
    public function actionWriteGroup()
    {
        $rules=post('rules');
        $id=post('id');
        if(isset($rules)){
            sort($rules);
            $rules  = implode( ',' , array_unique($rules));
        }
        $authgroup=new AuthGroup();
        if(!empty($id))
        {
            $authgroup=AuthGroup::model()->findByPk($id);
        }
        $authgroup->rules=$rules;
        $authgroup->module= 'admin';
        $authgroup->type=AuthGroup::TYPE_ADMIN;
        $authgroup->title=post('title');
        $authgroup->description=post('description');
        if(!isset($rules)&&!$authgroup->title)
        {
            $this->ajaxReturn('缺少必填项');
        }
        if($authgroup->save())
        {
            $this->ajaxReturn('操作成功',url('authManager/index'));
        }else {
            $this->ajaxReturn($this->get_model_ajax_error($authgroup),url('authManager/index'));
        }
    }
    
     /**
     * 访问授权页面
     */
    public function actionAccess(){
        $this->updateRules();
        
        $auth_group = AuthGroup::model()->findAll(array(
            'select'=>'id,title,rules',
            'condition'=>"status!=0 and module='admin' and type=".AuthGroup::TYPE_ADMIN,
        ));
        $auth_group=findall_field_to_array($auth_group,'id,title,rules');
        $node_list   = $this->returnNodes();
       
        $main_rules  = AuthRule::model()->findAll(array(
            'select'=>'name,id',
            'condition'=>"module='admin' and type=".AuthRule::RULE_MAIN." and status=1"
        ));
        
        $main_rules=findall_field_to_array($main_rules,'name,id');
      
        $child_rules = AuthRule::model()->findAll(
            array(
                'select'=>'name,id',
             	'condition'=>"module='admin' and type=".AuthRule::RULE_URL." and status=1"
            )
        );
        $child_rules=findall_field_to_array($child_rules,'name,id');
        $this->render('managergroup',
        array('main_rules'=>$main_rules,'auth_rules'=>$child_rules,'node_list'=>$node_list,'auth_group'=>$auth_group,
        'this_group'=>$auth_group[(int)$_GET['group_id']]));
    }
    
    
 	/**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     */
    public function updateRules(){
        //需要新增的节点必然位于$nodes
        $nodes    = $this->returnNodes(false);

        //需要更新和删除的节点必然位于$rules
        $rules    = AuthRule::model()->findAll(
            array(
                'condition'=>"module='admin' and type in (1,2)",
            )
        );
    
        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            if($value['pid'] >0){
                $temp['type'] = AuthRule::RULE_URL;
            }else{
                $temp['type'] = AuthRule::RULE_MAIN;
            }
            $temp['status']   = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        $rules=findall_to_array($rules);
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            }elseif($rule['status']==1){
                $ids[] = $rule['id'];
            }
        }

        if ( count($update) ) {
            foreach ($update as $k=>$row){
                if ( $row!=$diff[$row['id']] ) {
                    AuthRule::model()->updateByPk($row['id'],$row);
                    
                }
            }
        }
        
        if ( count($ids) ) {
            AuthRule::model()->updateAll(array('status'=>-1),"id in ( ".implode(',', $ids)." )");
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if( count($data) ){
            foreach ($data as $val)
            {
                $authrule=new AuthRule();
                foreach ($val as $k=>$v)
                {
                    $authrule->$k=$v;
                }
                $authrule->save();
            }
            
            //$AuthRule->addAll(array_values($data));
        }
        if ( AuthRule::model()->getErrors() ) {
            
            return false;
        }else{
            return true;
        }
    }
    
	/**
     * 用户组授权用户列表
     */
    public function actionUser($group_id){
        if(empty($group_id)){
            $this->ajaxReturn('参数错误');
        }

        $auth_group = AuthGroup::model()->findAll(
            array(
                'select'=>'id,title,rules',
                'condition'=>"status!=0 and module='admin' and type=".AuthGroup::TYPE_ADMIN,
            )
        ); 
        $auth_group=findall_field_to_array($auth_group,'id,title,rules');
        $prefix   = C('DB_PREFIX');
        $l_table  = $prefix.(AuthGroup::MEMBER);
        $r_table  = $prefix.(AuthGroup::AUTH_GROUP_ACCESS);
        $criteria=new CDbCriteria();
        $criteria->alias="m";
        $criteria->join="left join ".$r_table." a on m.uid=a.uid";
        $criteria->select="m.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status";
        $criteria->condition=" a.group_id=".$group_id." and m.status!=0";
        $criteria->order="m.uid asc";
        $_REQUEST = array();
        $lists = $this->lists("Member",$criteria);
        int_to_string($list);

        $this->render('user',array('lists'=>$lists,'auth_group'=>$auth_group,'this_group'=> $auth_group[(int)$_GET['group_id']],'pages'=>$this->pages));
    }
    
    
	/**
     * 将用户从用户组中移除  入参:uid,group_id
     */
    public function actionRemoveFromGroup(){
        $uid = get('uid');
        $gid = get('group_id');
        if( $uid==UID ){
            $this->ajaxReturn('不允许解除自身授权');
        }
        if( empty($uid) || empty($gid) ){
            $this->ajaxReturn('参数有误');
        }
        $AuthGroup = AuthGroup::model()->findByPk($gid);
        if( !$AuthGroup){
            $this->ajaxReturn('用户组不存在');
        }
        if ( $AuthGroup->removeFromGroup($uid,$gid) ){
            $this->ajaxReturn('操作成功',url('AuthManager/index'));
        }else{
            $this->ajaxReturn('操作失败',url('AuthManager/index'));
        }
    }
	/**
     * 将用户添加到用户组,入参uid,group_id
     */
    public function actionAddToGroup(){
        $uid = post('uid');
        $gid = post('group_id');
        if( empty($uid) ){
            $this->ajaxReturn('参数有误');
        }
      
        $AuthGroup = new AuthGroup();
        if(is_numeric($uid)){
            if ( is_administrator($uid) ) {
                $this->ajaxReturn('该用户为超级管理员');
            }
            if( ! Member::model()->find("uid in (:uid)",array(':uid'=>$uid))){
                $this->ajaxReturn('管理员用户不存在');
            }
        }

        if( $gid && (!$AuthGroup->checkGroupId($gid)||!$AuthGroup->checkUserId($uid))){
            $this->ajaxReturn($AuthGroup->error);
        }

        if ( $AuthGroup->addToGroup($uid,$gid) ){
            $this->ajaxReturn('操作成功',url('AuthManager/index'));
        }else{
            $this->ajaxReturn($AuthGroup->error);
        }
    }
}