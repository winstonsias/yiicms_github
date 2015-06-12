<?php
/*
**********PublicController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-27**********
*/
Yii::import('ext.user.api.UserApi');
class PublicController extends LjhController
{
    public function actionLogin()
    {
        $username=gp('username');
        $password=gp('password');
        $verify=gp('verify');
        if(IS_POST)
        {
        	/* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->ajaxReturn('验证码输入错误！');
            }
            $User = new UserApi;
            $uid = $User->login($username, $password);
            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = new Member();
                if($Member->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面
                    $this->ajaxReturn('登录成功！', $this->U('Index/index'),1);
                } else {
                    $this->ajaxReturn('登陆失败！');
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->ajaxReturn($error);
            }
        }else {
            if(is_login()){
                $this->redirect(array('Index/index'));
            }else{
                /* 读取数据库中的配置 */
                $config	= app()->cache->get('DB_CONFIG_DATA');
                if(!$config){
                    $config=new Config();
                    app()->cache->set('DB_CONFIG_DATA',$config->lists());
                    
                }
               $this->renderPartial('login',array('config'=>$config));
            }  
        }
    }
    
     public function actionVerify(){
        Yii::import('ext.verify.Verify');
        $verify = new Verify();
        $verify->entry(1);
    }
    public function actionLogout()
    {
         if(is_login()){
            $member=new Member();
            $member->logout();
            setSession('[destroy]',null);
            $this->success('退出成功！', 'public/login');
        } else {
            $this->redirect('login');
        }
    }
    
}