<?php
/*
**********UserApi.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-27**********
*/
Yii::import('ext.user.api.Api');
Yii::import('ext.user.models.User');
class UserApi extends Api
{
     /**
     * 构造方法，实例化操作模型
     */
    protected function _init(){
        $this->model = new User();
    }
 	/**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $type = 1){
        return $this->model->login($username, $password, $type);
    }
    
    public function register($username,$password,$email,$mobile = '')
    {
        return $this->model->register($username,$password,$email,$mobile);
    }
    
    public function updateInfo($uid,$oldpassword,$data)
    {
        return $this->model->updateUserFields($uid,$oldpassword,$data);
    }
    
}