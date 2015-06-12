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
**********ModelController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-21**********
*/
class ModelController extends BackendBaseController
{
	/**
     * 模型管理首页
     */
    public function actionIndex(){
        $criteria=new CDbCriteria();
        $criteria->condition='status > '.DELETE_VAL;
        $criteria->order='id desc';
        $list = $this->lists('DocModel',$criteria);
        int_to_string($list);

        $this->render('index',array('list'=>$list,'pages'=>$this->pages));
    }
    
    public function actionEdit()
    {
        $id = intval(gp('id'));
        if(!$id){
            $this->ajaxReturn('参数不能为空！');
        }
        if(IS_POST)
        {
            $this->update();
        }else
        {
            /*获取一条记录的详细数据*/
            $Model = new DocModel();
            $data = $Model->findByPk($id);
            if(!$data){
                $this->ajaxReturn($Model->getMyError());
            }
    
            $fields=Attribute::model()->findAll(
                array(
                    'condition'=>'model_id='.$data['id'],
                )
            );
            $fields=findall_to_array($fields);
            //是否继承了其他模型
            if($data['extend'] != 0){
                $extend_fields = Attribute::model()->findAll(
                    array(
                         'condition'=>'model_id='.$data['extend'],
                      
                    )
                );
                $extend_fields=findall_to_array($extend_fields);
                $fields = array_merge($fields, $extend_fields);
            }
    
            /* 获取模型排序字段 */
            $field_sort = json_decode($data['field_sort'], true);
            if(!empty($field_sort)){
            	/* 对字段数组重新整理 */
            	$fields_f = array();
            	foreach($fields as $v){
            		$fields_f[$v['id']] = $v;
            	}
            	$fields = array();
            	foreach($field_sort as $key => $groups){
            		foreach($groups as $group){
            			$fields[$fields_f[$group]['id']] = array(
            					'id' => $fields_f[$group]['id'],
            					'name' => $fields_f[$group]['name'],
            					'title' => $fields_f[$group]['title'],
            					'is_show' => $fields_f[$group]['is_show'],
            					'group' => $key
            			);
            		}
            	}
            	
            	/* 对新增字段进行处理 */
            	$new_fields = array_diff_key($fields_f,$fields);
            	foreach ($new_fields as $value){
            	    $value['group']=1;//默认是第一个分组
            		if($value['is_show'] == 1){
            			array_unshift($fields, $value);
            		}
            	}
            }else {
                foreach ($fields as &$val)
                {
                    $val['group']=1;//添加默认分组
                }
            }
    
            $this->render('edit',array('fields'=>$fields,'info'=>$data));
        }
    }
    
    
    
 	/**
     * 更新一条数据
     */
    public function update(){
        $docmodel=new DocModel();
        $res =$docmodel->winston_update();

        if(!$res){
            $this->ajaxReturn($docmodel->getMyError());
        }else{
            app()->cache->set('DOCUMENT_MODEL_LIST', ''); //更新缓存
            $this->ajaxReturn(isset($res['id'])?'更新成功':'新增成功', app()->request->urlReferrer);
        }
    }
    public function actionUpdate()
    {
        $this->update();
    }
    //新增
    public function actionAdd()
    {
        $models = DocModel::model()->findAll(
            array(
                'condition'=>'extend=0',
            )
        );
        $models=findall_to_array($models);
        $this->render('add',array('models'=>$models));
    }
    
    //生成模型表
    public function actionGenerate()
    {
         if(!IS_POST){
            //获取所有的数据表
            $tables = DocModel::model()->getTables();

            $this->render('generate',array('tables'=>$tables));
        }else{
            $table = post('table');
            empty($table) && $this->ajaxReturn('请选择要生成的数据表！');
            $res = DocModel::model()->generate($table);
            if($res){
                $this->ajaxReturn('生成模型成功！', url('model/index'));
            }else{
                $this->ajaxReturn(DocModel::model()->getMyError());
            }
        }
    }
    
    //删除
    public function actionDelete()
    {
        $ids = intval(get('ids'));
        if(!$ids)
        {
            $this->ajaxReturn('参数不能为空！');
        }
        $ids = explode(',', $ids);
        foreach ($ids as $value){
            $res = DocModel::model()->del($value);
            if(!$res){
                break;
            }
        }
        if(!$res){
            $this->ajaxReturn(DocModel::model()->getMyError());
        }else{
            $this->ajaxReturn('删除模型成功！',app()->request->urlReferrer);
        }
    }
}