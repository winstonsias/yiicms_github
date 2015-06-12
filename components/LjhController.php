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
**********LjhController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class LjhController extends CController
{
    public function init()
    {
        parent::init();
        defined('IS_POST') or define('IS_POST', app()->request->isPostRequest);
        defined('IS_AJAX') or define('IS_AJAX', app()->request->isAjaxRequest);
        defined('IS_GET')  or  define('IS_GET',  app()->request->requestType=='GET'?true:false);
    }
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    //public $layout='//layouts/column1';
        //public $layout=false;
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();
    public $pages;//分页对象 
    public $total;//列表总数
     /**
     * 成功提示
     * @param type $msg 提示信息
     * @param type $jumpurl 跳转url
     * @param type $wait 等待时间
     */
    public function success($msg="",$jumpurl="",$wait=3){
        self::_jump($msg, $jumpurl, $wait, 1);
    }
    /**
     * 错误提示
     * @param type $msg 提示信息
     * @param type $jumpurl 跳转url
     * @param type $wait 等待时间
     */
    public function error($msg="",$jumpurl="",$wait=3){
        self::_jump($msg, $jumpurl, $wait, 0);
    }
    /**
     * 最终跳转处理
     * @param type $msg 提示信息
     * @param type $jumpurl 跳转url
     * @param type $wait 等待时间
     * @param int $type 消息类型 0或1
     */
    public function _jump($msg="",$jumpurl="",$wait=3,$type=0){
        //生成URL地址
        if(is_array($jumpurl)){
            $jumpurl = $this->U($jumpurl[0],$jumpurl[1]);
        }elseif($jumpurl){
            $jumpurl = $this->U($jumpurl);
        }
        $data = array(
            'msg' => $msg,
            'jumpurl' => $jumpurl,
            'wait' => $wait,
            'type' => $type
        );
        $data['title'] = ($type==1) ? "提示信息" : "错误信息";
        if(empty($jumpurl)){
            if($type==1){
                $data['jumpurl']=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"javascript:window.close();";
            }else{
                $data['jumpurl'] = "javascript:history.back(-1);";
            }
        }
         
        $this->renderPartial("//sys/message",array('data'=>$data));
    }
     
    /*
     * 根据参数拼成路由
     * @param string actionName方法名
     * @param array $param 参数 
     */
    public function U($actionName,$param=''){
        if($param){
            return $this->createUrl($actionName, $param);
        }else{
            return $this->createUrl($actionName);
        }        
    }
     
    /**
     * Ajax方式返回数据到客户端
     * @access public
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     */
    public function ajaxReturn($message,$jumpurl='', $status=0,$addparams=array(),$type = 'JSON') {
        $data           =   array();
        $data['url']=$jumpurl;
        $data['info']   =   $message;
        $data['status'] =   $status;
        
        $data=CMap::mergeArray($data, $addparams);
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML' :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit($this->xml_encode($data));
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default :
                // 其他返回格式抛出异常
                exit('该数据格式尚未支持，请修改本函数源码添加对应的头');
        }
    }
     
     
     
     
    /*
     * dump方法
     * @param $data 打印的数据
     */
    public function dump($data){
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
    /**
     * 
     * 根据model以及条件返回 lists 和 page
     * @param unknown_type $model model
     * @param unknown_type $criteria
     */
    public function lists($model,$criteria)
    {
        $count=$model::model()->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=C('PAGE_SIZE');
        $pages->applyLimit($criteria);
        
        $criteria->limit=$pages->pageSize;
        $criteria->offset=$pages->currentPage*$pages->pageSize;
        $lists=$model::model()->findAll($criteria);
        
        $this->pages=$pages;
        $this->total=$count;
        return int_to_string(findall_to_array($lists));
    }
    
    public function process_model($model,$attributes,$condition,$param=array())
    {
        
        try{
            $model::model()->updateAll($attributes,$condition,$param);
            return "修改成功";
        }catch (CDbException $e){
            return $e->getMessage();
        }
           
        
    }
    
    
	/**
     * 更改状态    禁用  启用 删除
     * Enter description here ...
     * @param unknown_type $method
     */
    public function actionSetStatus($status=null,$model=null,$action=null,$param='')
    {
        $id = array_unique((array)gp('ids'));
        $id = is_array($id) ? implode(',', $id) : (array)$id;
        if ( empty($id) ) {
            $this->ajaxReturn('请选择要操作的数据!');
        }

        $condition="id in ($id)";
        $attributes=array();
        $result="异常出错！";
        switch ($status)
        {
            case RESUME_VAL:
                $attributes['status']=RESUME_VAL;
                break;
            case FORBID_VAL:
                $attributes['status']=FORBID_VAL;
                break;
            case DELETE_VAL:
                $attributes['status']=DELETE_VAL;
                break;
        }
        $model=is_null($model)?ucfirst($this->getId()):ucfirst($model);
        $action=is_null($action)?'index':$action;
        
        if(!empty($attributes))
        {
           $result= $this->process_model($model,$attributes,$condition);
        }
        $model=ucfirst($this->getId());
        $param=unserialize($param);
        if(is_array($param))
        {
            $this->ajaxReturn($result,url($model.'/'.$action,$param));
        }
        else
        {
            $this->ajaxReturn($result,url($model.'/'.$action));
        }
    }

    //获取model信息 ajax提示
    public function get_model_ajax_error($model)
    {
        $errors=$model->getErrors();
        $str="";
        foreach($errors as $k=>$v)
        {
            $str.=$v[0];
        }
        return $str;
    }
    
}