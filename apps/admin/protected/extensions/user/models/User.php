<?php
/*
**********user.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-27**********
*/
class User extends CActiveRecord
{
    public function tableName()
    {
        return "{{ucenter_member}}";
    }
    
    
	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($username, $password, $type = 1){
		$criteria=new CDbCriteria();
		switch ($type) {
			case 1:
			    $criteria->condition='username=:username';
				break;
			case 2:
			    $criteria->condition='email=:username';
				break;
			case 3:
			    $criteria->condition='mobile=:username';
				break;
			case 4:
			     $criteria->condition='id=:username';
				break;
			default:
				return 0; //参数错误
		}
        $criteria->params=array(':username'=>$username);
        $user=$this->find($criteria);
		if($user&&$user->status)
		{
		    if(think_ucenter_md5($password, UC_AUTH_KEY) === $user->password){
				$this->updateLogin($user); //更新用户登录信息
				return $user->id; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		}else {
		    return -1; //用户不存在或被禁用
		}
	}
	
	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($user){
	    $user->last_login_time=time();
	    $user->last_login_ip= Yii::app()->request->userHostAddress;
		$user->save();
	}
	
	/**
	 * 注册一个新用户
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($username, $password, $email, $mobile){
	    
	    $this->username=$username;
	    $this->password=think_ucenter_md5($password,UC_AUTH_KEY);
	    $this->email=$email;
	    $this->mobile=$mobile;
	    $this->status=RESUME_VAL;
		
		//验证手机
		if(empty($this->mobile)) unset($this->mobile);
        $uid=0;
		/* 添加用户 */
		if($uid==$this->save()){
			return $uid; //0-未知错误，大于0-注册成功
		} else {
			return $this->getErrors(); //错误详情见自动验证注释
		}
	}
	
	public function updateUserFields($uid,$password,$data)
	{
	    if(empty($uid) || empty($password) || empty($data)){
			return '参数错误！';
		}

		//更新前检查用户密码
		if(!$this->verifyUser($uid, $password)){
			return '验证出错：密码不正确！';
		}
		$user=$this->findByPk($uid);
		foreach ($data as $k=>$v)
		{
		    if($k=='password')
		    {
		        $v=think_ucenter_md5($v,UC_AUTH_KEY);
		    }
		    $user->$k=$v;
		}
		try
		{
		    $user->save();
		    return "修改成功！";
		}catch (CDbException $e) {
		    return $e->getMessage();
		}
	}
	
	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 */
	protected function verifyUser($uid, $password_in){
		$user = $this->find(array('select'=>'password','condition'=>'id='.$uid));
		if(think_ucenter_md5($password_in, UC_AUTH_KEY) === $user->password){
			return true;
		}
		return false;
	}
}