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
**********Memeber.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class Member extends LjhModel
{
    public function tableName()
    {
        return "{{member}}";
    }
    
    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $criteria=new CDbCriteria();
        $criteria->condition='uid=:uid';
        $criteria->params=array(':uid'=>$uid);
        $user = $this->find($criteria);
        if(!$user || 1 != $user->status) {
            return false;
        }

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        setSession('user_auth', null);
        setSession('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $user->login+=1;
        $user->last_login_time=NOW_TIME;
        $user->last_login_ip=Yii::app()->request->userHostAddress;
        $user->update();

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user->uid,
            'username'        => $user->nickname,
            'last_login_time' => $user->last_login_time,
        );

        setSession('user_auth', $auth);
        setSession('user_auth_sign', data_auth_sign($auth));

    }

    public function getNickName($uid){
         $criteria=new CDbCriteria();
         $criteria->select='nickname';
        $criteria->condition='uid='.$uid;
        $user = $this->find($criteria);
        return $user;
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
}