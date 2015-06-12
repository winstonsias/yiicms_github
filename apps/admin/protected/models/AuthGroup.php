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
**********AuthManager.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-3**********
*/
class AuthGroup extends LjhModel
{
    const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
    const MEMBER                    = 'member';
    const UCENTER_MEMBER            = 'ucenter_member';
    const AUTH_GROUP_ACCESS         = 'auth_group_access'; // 关系表表名
    const AUTH_EXTEND               = 'auth_extend';       // 动态权限扩展信息表
    const AUTH_GROUP                = 'auth_group';        // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识
    
    public  $error;

    public function tableName()
    {
     return "{{auth_group}}";
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
 	/**
     * 将用户从用户组中移除
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     */
    public function removeFromGroup($uid,$gid){
        $condition='uid=:uid and group_id=:group_id';
        $params=array(':uid'=>$uid,'group_id'=>$gid);
        $model=new DynamicModel("{{".self::AUTH_GROUP_ACCESS."}}");
        return $model::model()->deleteAll($condition,$params);
    }
    
     /**
     * 把用户添加到用户组,支持批量添加用户到用户组

     * 示例: 把uid=1的用户添加到group_id为1,2的组 `AuthGroupModel->addToGroup(1,'1,2');`
     */
    public function addToGroup($uid,$gid){
        $uid = is_array($uid)?implode(',',$uid):trim($uid,',');
        $gid = is_array($gid)?$gid:explode( ',',trim($gid,',') );

        $Access = new DynamicModel("{{".self::AUTH_GROUP_ACCESS."}}");

        //if( isset($_REQUEST['batch']) ){
            //为单个用户批量添加用户组时,先删除旧数据
            $del = $Access->deleteAll("uid in ($uid)");
        //}

        $uid_arr = explode(',',$uid);
		$uid_arr = array_diff($uid_arr,array(C('USER_ADMINISTRATOR')));
        $add = array();
     
            foreach ($uid_arr as $u){
                foreach ($gid as $g){
                    if( is_numeric($u) && is_numeric($g) ){
                        $Access->group_id=$g;
                        $Access->uid=$u;
                        $Access->save();
                    }
                }
            }
        
        if ($Access->getErrors()) {
            if( count($uid_arr)==1 && count($gid)==1 ){
                //单个添加时定制错误提示
                $this->error = "不能重复添加";
            }
            return false;
        }else{
            return true;
        }
    }
    
	/**
     * 检查id是否全部存在
     * @param array|string $gid  用户组id列表
     */
    public function checkId($modelname,$mid,$pk,$msg = '以下id不存在:'){
        if(is_array($mid)){
            $count = count($mid);
            $ids   = implode(',',$mid);
        }else{
            $ids   = $mid;
            $mid   = explode(',',$mid);
            $count = count($mid);
            
        }

        $s = $modelname::model()->findAll(
            array(
                'condition'=>$pk." in ($ids)",
                'select'=>$pk,
            )
        );
        $s=findall_field_to_array($s,$pk,false);
        if(count($s)===$count){
            return true;
        }else{
            $diff = implode(',',array_diff($mid,$s));
            $this->error = $msg.$diff;
            return false;
        }
    }

    /**
     * 检查用户组是否全部存在
     * @param array|string $gid  用户组id列表
     */
    public function checkGroupId($gid){
        return $this->checkId('AuthGroup',$gid,'id', '以下用户组id不存在:');
    }
    
    public function checkUserId($uid)
    {
        return $this->checkId('Member',$uid, 'uid','以下用户id不存在:');
    }
    
   /**
     * 返回用户拥有管理权限的分类id列表
     * 
     * @param int     $uid  用户id
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     */
    static public function getAuthCategories($uid){
        return self::getAuthExtend($uid,self::AUTH_EXTEND_CATEGORY_TYPE,'AUTH_CATEGORY');
    }
    
	/**
     * 返回用户拥有管理权限的扩展数据id列表
     * 
     * @param int     $uid  用户id
     * @param int     $type 扩展数据标识
     * @param int     $session  结果缓存标识
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     */
    static public function getAuthExtend($uid,$type,$session){
        if ( !$type ) {
            return false;
        }
        if ( $session ) {
            $result = getSession($session);
        }
        if ( $uid == UID && !empty($result) ) {
            return $result;
        }
        $prefix = C('DB_PREFIX');
        $connection = Yii::app()->db;
        $sql="select extend_id from ".$prefix.self::AUTH_GROUP_ACCESS." g join ".$prefix.self::AUTH_EXTEND.' c on g.group_id=c.group_id'.
        " where g.uid='$uid' and c.type='$type' and !isnull(extend_id)";
        $result=$connection->createCommand($sql)->queryAll();
        if ( $uid == UID && $session ) {
            setSession($session,$result);
        }
        return $result;
    }
}