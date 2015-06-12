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
**********AttributeController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-24**********
*/
class AttributeController extends BackendBaseController
{
    public function actionIndex()
    {
        $model_id=gp('model_id');
        if(!$model_id)
        {
            $this->ajaxReturn('参数错误');
        }
        $criteria=new CDbCriteria();
        $criteria->condition='model_id='.$model_id;
        $criteria->order='id desc';
        $list = $this->lists('Attribute', $criteria);
        int_to_string($list);
        $this->render('index',array('_list'=>$list,'model_id'=>$model_id));
    }
    
    public function actionAdd()
    {
        $model_id=gp('model_id');
        if(!$model_id)
        {
            $this->ajaxReturn('参数错误');
        }
        if(IS_POST)
        {
            $this->update();
        }else {
            $model=DocModel::model()->findByPk($model_id);
            $this->render('add',array('model'=>$model,'info'=>array('model_id'=>$model_id)));
        }
    }
    /**
     * 编辑页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function actionEdit(){
        $id = intval(get('id'));
        if(!$id){
            $this->ajaxReturn('参数不能为空！');
        }
        if(IS_POST)
        {
            $this->update();
        }else {
            /*获取一条记录的详细数据*/
            $attribute = new Attribute();
            $data = $attribute->findByPk($id);
            if(!$data){
                $this->ajaxReturn($attribute->getMyError());
            }
            $model  = DocModel::model()->findByPk($data['model_id']);
            $this->render('add',array('model'=>$model,'info'=>$data));
        }
    }
    
    private function update()
    {
         $res = Attribute::model()->winston_update();
        if(!$res){
            $this->ajaxReturn(Attribute::model()->getMyError());
        }else{
            $this->ajaxReturn($res['id']?'更新成功':'新增成功', app()->request->urlReferrer);
        }
    }
    
    public function actionDelete()
    {
        $id = intval(get('id'));
        if(!$id){
            $this->ajaxReturn('参数不能为空！');
        }

        $Model = new Attribute();

        $info = $Model->findByPk($id);
        empty($info) && $this->ajaxReturn('该字段不存在！');
        
        $res=$Model->winston_delete($id);
        if(!$res){
            $this->ajaxReturn($Model->getMyError());
        }else{
            $this->ajaxReturn('删除成功', url('attribute/index',array('model_id'=>$info['model_id'])));
        }
    }
}