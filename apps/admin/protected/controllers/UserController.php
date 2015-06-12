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
**********UserController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-29**********
*/
class UserController extends BackendBaseController
{
    public function actionIndex()
    {
        $nickname=get('nickname');
        $criteria=new CDbCriteria();
        if($nickname)
        {
            if(is_numeric($nickname))
            {
                $criteria->condition='uid=:nickname';
            }else {
                $criteria->condition="nickname like '%$nickname%'";
            }
            $criteria->params=array(':nickname'=>$nickname);
        }
        $lists=$this->lists('Member', $criteria);
        $this->render('index',array('lists'=>$lists,'pages'=>$this->pages));
    }
    /**
     * 添加用户
     * Enter description here ...
     */
    public function actionAdd()
    {
        if(IS_POST)
        {   
            $username=post('username');
            $password=post('password');
            $repassword=post('repassword');
            $email=post('email');
            if(!$username||!$password||!$email)
            {
                $this->ajaxReturn('用户名、密码、邮箱不能为空！');
            }
            if($password != $repassword){
                $this->ajaxReturn('密码和重复密码不一致！');
            }
            Yii::import('ext.user.api.UserApi');
            $user=new UserApi();
            $uid=$user->register($username,$password,$email);
             if(0 < $uid){ //注册成功
                 $member=new Member();
                 $member->uid=$uid;
                 $member->nickname=$username;
                 $member->status=1;
                if(!$member->save()){
                    $this->ajaxReturn('用户添加失败！');
                } else {
                    $this->ajaxReturn('用户添加成功！',url('user/index'));
                }
            } else { //注册失败，显示错误信息
                $this->ajaxReturn($this->get_model_ajax_error($member));
            }
        }else {
            $this->render('add');
        }
    }
    /**
     * 更改状态    禁用  启用 删除
     * Enter description here ...
     * @param unknown_type $method
     */
    public function actionChangeStatus($method=null)
    {
        $id = array_unique((array)gp('id'));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->ajaxReturn("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',', $id) : (array)$id;
        if ( empty($id) ) {
            $this->ajaxReturn('请选择要操作的数据!');
        }

        $condition="uid in ($id)";
        $attributes=array();
        $result="异常出错！";
        switch ($method)
        {
            case 'resumeUser':
                $attributes['status']=RESUME_VAL;
                break;
            case 'forbidUser':
                $attributes['status']=FORBID_VAL;
                break;
            case 'deleteUser':
                $attributes['status']=DELETE_VAL;
                $attributes['update_time']=NOW_TIME;
                break;
        }
        
        if(!empty($attributes))
        {
           $result= $this->process_model('Member',$attributes,$condition);
        }

        $this->ajaxReturn($result,url('user/index'));
    }
    
    public function actionUpdatepassword()
    {
        if(IS_POST)
        {
            $oldpassword=post('oldpassword');
            $password=$data['password']=post('password');
            $repassword=post('repassword');
            if(empty($oldpassword)||empty($password)||empty($repassword))
            {
                $this->ajaxReturn('信息填写不完整！');
            }
            if($password!==$repassword)
            {
                $this->ajaxReturn('您输入的新密码与确认密码不一致！');
            }
            Yii::import('ext.user.api.UserApi');
            $Api=new UserApi();
            $res=$Api->updateInfo(UID, $oldpassword, $data);
            $this->ajaxReturn($res);
        }else {
            $this->render('updatepassword');
        }
    }
    
    public function actionUpdateNickname()
    {
        if(IS_POST){
        
             //获取参数
            $nickname = post('nickname');
            $password = post('password');
            empty($nickname) && $this->ajaxReturn('请输入昵称');
            empty($password) && $this->ajaxReturn('请输入密码');
             Yii::import('ext.user.api.UserApi');
            //密码验证
            $User   =   new UserApi();
            $uid    =   $User->login(UID, $password, 4);
            ($uid == -2) && $this->ajaxReturn('密码不正确');
    
            $Member =   new Member();
            
            $res   = $Member->updateByPk($uid, array('nickname'=>$nickname));
      
            if($res){
                $user               =   getSession('user_auth');
                $user['username']   =   $nickname;
                setSession('user_auth', $user);
                setSession('user_auth_sign', data_auth_sign($user));
                $this->ajaxReturn('修改昵称成功！');
            }else{
                $this->ajaxReturn('修改昵称失败！');
            }
        }else {
        
             $nickname = Member::model()->getNickName(UID);
             $nickname=$nickname->nickname;
             $this->render('updatenickname',array('nickname'=>$nickname));
        }
    }
}