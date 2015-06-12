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
**********ChannelController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-12-2**********
*/
class ChannelController extends BackendBaseController
{
    public function actionIndex()
    {
        $pid = intval(get('pid'));
        /* 获取频道列表 */
        $criteria=new CDbCriteria();
        $criteria->addCondition('status>-1 and pid='.$pid);
        $criteria->order='sort,id';
        $list = Channel::model()->findAll($criteria); 
        $this->render('index',array('list'=>$list,'pid'=>$pid));
    }
    //新增
    public function actionAdd()
    {
        if(IS_POST){
            $data=$_POST;
            $Channel = new Channel();
            $Channel->attributes=$data;
            if($Channel->validate()){
                $id = $Channel->save();
                $id=$Channel->id;
                if($id){
                    $this->ajaxReturn('新增成功', url('channel/index'));
                } else {
                    $this->ajaxReturn('新增失败');
                }
            } else {
                $this->ajaxReturn($this->get_model_ajax_error($Channel));
            }
        } else {
            $pid =intval(get('pid'));
            $parent=null;
            //获取父导航
            if(!empty($pid)){
                $parent = Channel::model()->find(
                    array(
                        'condition'=>'id='.$pid,
                    )
                );
             
            }

            $this->render('edit',array('pid'=>$pid,'info'=>null,'parent'=>$parent));
        }
    }
    
    
     //删除
    public function actionDelete()
    {
        $id = array_unique((array)gp('id'));
        //$id = is_array($id) ? implode(',', $id) : (array)$id;
        if ( empty($id) ) {
            $this->ajaxReturn('请选择要操作的数据!');
        }
        $criteria=new CDbCriteria();
        $criteria->addInCondition('id', $id);
         if(Channel::model()->deleteAll($criteria)){
            $this->ajaxReturn('删除成功',url('channel/index'));
        } else {
            $this->ajaxReturn('删除失败！');
        }
    }
    //编辑
    public function actionEdit()
    {
        $id=gp('id');
        if(!$id)
        {
            $this->ajaxReturn('参数错误！');
        }
        if(IS_POST){
            $data=$_POST;
            $Channel = new Channel();
            $Channel->attributes=$data;
            if($Channel->validate()){
                if($Channel->updateByPk($id, $data)){
                    $this->ajaxReturn('编辑成功', url('channel/index'));
                } else {
                    $this->ajaxReturn('编辑失败');
                }

            } else {
                $this->ajaxReturn($this->get_model_ajax_error($Channel));
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info =  Channel::model()->findByPk($id);

            if(false === $info){
                $this->ajaxReturn('获取信息错误');
            }
            $parent=null;
            $pid = intval(get('pid'));
            //获取父导航
            if(!empty($pid)){
            	 $parent = Channel::model()->find(
                    array(
                        'condition'=>'id='.$pid,
                    )
                );
            }

             $this->render('edit',array('pid'=>$pid,'info'=>$info,'parent'=>$parent));
        }
    }
    //排序
    public function actionSort()
    {
        if(IS_GET){
            $ids = get('ids');
            $pid = get('pid');

            //获取排序的数据
            $criteria=new CDbCriteria();
            $criteria->condition='status>-1';
            if(!empty($ids)){
                $ids=explode(',', $ids);
                $criteria->addInCondition('id', $ids);
            }else{
                if($pid !== ''){
                    $criteria->addCondition('pid='.$pid);
                }
            }
            $criteria->order='sort,id';
            $list = Channel::model()->findAll($criteria);

            $this->render('sort',array('list'=>$list));
        }elseif (IS_POST){
            $ids = post('ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = Channel::model()->updateByPk($value, array('sort'=>$key+1));
            }
            if($res !== false){
                $this->ajaxReturn('排序成功！',url('channel/index'));
                
            }else{
                $this->ajaxReturn('排序失败！');
            }
        }else{
            $this->ajaxReturn('非法请求！');
        }
    }
}