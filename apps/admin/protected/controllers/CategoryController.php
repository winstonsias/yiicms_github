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
**********CategoryController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-11**********
*/
class CategoryController extends BackendBaseController
{
    public function actionIndex()
    {
        $tree = Category::model()->getTree(0,'id,name,title,sort,pid,allow_publish,status');
        $this->render('index',array('tree'=>$tree));
    }
    
    public function tree($tree)
    {
        $this->renderPartial('tree',array('tree'=>$tree));
        
    }
	/* 新增分类 */
    public function actionAdd($pid = 0){
        $Category = new Category();

        if(IS_POST){ //提交表单
            $data=post_to_one_array($_POST);
            $data['create_time']=time();
            $data['update_time']=time();
            $data['status']=1;
            $Category->attributes=$data;
            if(false !== $Category->save()){
                $this->ajaxReturn('新增成功！', url('category/index'));
            } else {
                $error = $this->get_model_ajax_error($Category);
                $this->ajaxReturn(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }
            
            /* 获取分类信息 */
            $this->render('edit',array('category'=>$cate));
        }
    }
    //编辑分类
    public function actionEdit($id = null, $pid = 0){

        $Category = Category::model()->findByPk($id);

        if(IS_POST){ //提交表单
             $data=post_to_one_array($_POST);
             $data['update_time']=time();
             $data['model']=isset($data['model'])?$data['model']:'';
             $data['type']=isset($data['type'])?$data['type']:'';
             $Category->attributes=$data;
            if(false !== $Category->update()){
                $this->ajaxReturn('编辑成功！', url('category/index'));
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->ajaxReturn('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $info = $id ? $Category->info($id) : '';

            $this->render('edit',array('info'=>$info,'category'=>$cate));
        }
    }
    
   //删除分类
   public function actionDelete()
   {
        $cate_id =  get('id');
        if(empty($cate_id)){
            $this->ajaxReturn('参数错误!');
        }

        //判断该分类下有没有子分类，有则不允许删除
        $child = Category::model()->findAll(
            array(
                'condition'=>'pid='.$cate_id
            )
        );
        if($child){
            $this->ajaxReturn('请先删除该分类下的子分类');
        }

        //判断该分类下有没有内容 
        $document_list = Document::model()->count("category_id=".$cate_id);
          
        if($document_list>0){
            $this->ajaxReturn('请先删除该分类下的文章（包含回收站）');
        }

        //删除该分类信息
        $res = Category::model()->deleteByPk($cate_id);
        if($res !== false){
            
            $this->ajaxReturn('删除分类成功！',url('category/index'));
        }else{
            $this->ajaxReturn('删除分类失败！');
        }
   }
   
   public function actionOperate($type,$from)
   {
        switch ($type)
        {
           case 'move':
               $operate='转移';
               break;
           case 'merge':
               $operate='合并';
               break;
           default:
               $this->ajaxReturn('参数错误');
        }
        if(!$from)
        {
           $this->ajaxReturn('参数错误');
        }

        //获取分类
        $map = array('status'=>1, 'id'=>array('neq', $from));
        $list = Category::model()->findAll(
            array(
                'condition'=>'status=1 and id!='.$from,
                'select'=>'id,title'
            )
        );
        $list=findall_to_array($list);
        $this->render('operate',array('type'=>$type,'from'=>$from,'list'=>$list,'operate'=>$operate));

   }
   
   //转移
   public function actionMove()
   {
        $to = intval(post('to'));
        $from = intval(post('from'));
        if(!$to||!$from)
        {
            $this->ajaxReturn('参数错误！');
        }
        $category=Category::model()->findByPk($from);
        $category->pid=$to;
        $res =$category->save();
        if($res !== false){
            $this->ajaxReturn('分类移动成功！', url('category/index'));
        }else{
            $this->ajaxReturn('分类移动失败！');
        }
   }
   
   //合并
   public function actionMerge()
   {
        $to = intval(post('to'));
        $from = intval(post('from')); 
        $category = new Category();

        $from_category=$category->findByPk($from);
        $to_category=$category->findByPk($to);
        //检查分类绑定的模型
        $from_models = explode(',', $from_category['model']);
        $to_models = explode(',', $to_category['model']);
        foreach ($from_models as $value){
            if(!in_array($value, $to_models)){
                $this->ajaxReturn('请给目标分类绑定' . get_document_model($value, 'title') . '模型');
            }
        }

        //检查分类选择的文档类型
        $from_types = explode(',', $from_category['type']);
        $to_types = explode(',', $to_category['type']);
        foreach ($from_types as $value){
            if(!in_array($value, $to_types)){
                $types = C('DOCUMENT_MODEL_TYPE');
                $this->ajaxReturn('请给目标分类绑定文档类型：' . $types[$value]);
            }
        }

        //合并文档
        $res = Document::model()->updateAll(array('category_id'=>$to),"category_id=".$from);
        if($res){
            //删除被合并的分类
            $from_category->delete();
            $this->ajaxReturn('合并分类成功！', url('category/index'));
        }else{
            $this->ajaxReturn('合并分类失败！');
        }
   }
}