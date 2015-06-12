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
**********ConfigController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-4**********
*/
class ConfigController extends BackendBaseController
{
	/**
     * 配置管理
     */
    public function actionIndex(){
        /* 查询条件初始化 */
        $criteria=new CDbCriteria();
        $criteria->condition='status='.RESUME_VAL;
        if(isset($_GET['group'])){
            $criteria->addCondition('`group`='.intval(gp('group')));
        }
        if(isset($_GET['name'])){
            $criteria->addCondition("name like '%".(string)gp('name')."%'");
        }
        $criteria->order='sort,id';
        $list = $this->lists('Config', $criteria);


        $this->render('index',array('group'=>C('CONFIG_GROUP_LIST'),'group_id'=>intval(get('group')),'list'=>$list));
    }
    //编辑
    public function actionEdit()
    {
        $id=intval(gp('id'));
        if(!$id)
        {
            $this->ajaxReturn('参数错误');
        }
        if(IS_POST){
            $data=$_POST;
            $config=new Config();
            $status=$config->updateByPk($id, $data);
            if($status){
                app()->cache->set('DB_CONFIG_DATA', NULL);
               $this->ajaxReturn('更新成功', app()->request->urlReferrer);
                
            } else {
                $this->ajaxReturn('更新失败');
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = Config::model()->findByPk($id);

            if(false === $info){
                $this->ajaxReturn('获取配置信息错误');
            }

            $this->render('edit',array('info'=>$info));
        }
    }
    //新增
    public function actionAdd()
    {
         if(IS_POST){
            $data=$_POST;
            $Config =new Config();
            $Config->attributes=$data;
            if($Config->validate()){
                if($Config->save()){
                    app()->cache->set('DB_CONFIG_DATA', NULL);
                    $this->ajaxReturn('新增成功', url('config/index'));
                } else {
                    $this->ajaxReturn('新增失败');
                }
            } else {
                $this->ajaxReturn($this->get_model_ajax_error($Config));
            }
        } else {
         
            $this->render('edit',array('info'=>null));
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
         if(Config::model()->deleteAll($criteria)){
            app()->cache->set('DB_CONFIG_DATA', NULL);
            $this->ajaxReturn('删除成功',url('config/index'));
        } else {
            $this->ajaxReturn('删除失败！');
        }
    }
    //排序
    public function actionSort()
    {
        if(IS_GET){
            $ids = get('ids');
            $criteria=new CDbCriteria();
            //获取排序的数据
            $criteria->condition='status>-1';
            if(!empty($ids)){
                $ids=explode(',', $ids);
                $criteria->addInCondition('id', $ids);
            }elseif(intval(gp('group'))){
                $criteria->addCondition('`group`='.gp('group'));
            }
            $criteria->order='sort,id';
            $list = Config::model()->findAll($criteria);
            $list=findall_to_array($list);

            $this->render('sort',array('list'=>$list));
        }elseif (IS_POST){
            $ids = post('ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
               
                $res =  Config::model()->updateByPk($value, array('sort'=>$key+1));
            }
            if($res !== false){
                $this->ajaxReturn('排序成功！',url('config/index'));
            }else{
                $this->ajaxReturn('排序失败！');
            }
        }else{
            $this->ajaxReturn('非法请求！');
        }
    }
    public function actionGroup()
    {
        $id    =get('id')?get('id'):1;
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =Config::model()->findAll(
            array(
                'condition'=>"`status`=1 and `group`=".$id,
                'select'=>'id,name,title,extra,value,remark,type',
                'order'=>'sort',
            )
        );  

        $this->pageTitle = $type[$id].'设置';
        $this->render('group',array('id'=>$id,'list'=>$list));
    }
    public function actionSave()
    {
        $config=post('config');
         if($config && is_array($config)){
            foreach ($config as $name => $value) {
                $config=Config::model()->find('name=:name',array(':name'=>$name));
                $config->value=$value;
                $config->save();
            }
        }
        app()->cache->set('DB_CONFIG_DATA', null);
        $this->ajaxReturn('保存成功！');
    }
}