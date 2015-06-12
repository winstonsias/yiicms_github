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
**********ArticleController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-12**********
*/
class ArticleController extends BackendBaseController
{
    private $cate_id        =   null; //文档分类id
    public $assign=array();//赋值容器
	/**
     * 显示左边菜单，进行权限控制
     */
    protected function getMenu(){
        //获取动态分类
        $cate_auth  =   AuthGroup::getAuthCategories(UID);	//获取当前用户所有的内容权限节点
        $cate_auth  =   $cate_auth == null ? array() : $cate_auth;
        $cate       =   Category::model()->findAll(
            array(
                'condition'=>'status=1',
                'select'=>'id,title,pid,allow_publish',
                'order'=>'pid asc,sort asc',
            )
        );
        $cate=findall_to_array($cate);
        //没有权限的分类则不显示
        if(!IS_ROOT){
            foreach ($cate as $key=>$value){
                if(!in_array($value['id'], $cate_auth)){
                    unset($cate[$key]);
                }
            }
        }

        $cate           =   list_to_tree($cate);	//生成分类树

        //获取分类id
        $cate_id        =   gp('cate_id');
        $this->cate_id  =   $cate_id;

        //是否展开分类
        $hide_cate = false;
        if($this->action->id != 'recycle' && $this->action->id != 'draftbox' && $this->action->id != 'mydocument'){
            $hide_cate  =   true;
        }

        //生成每个分类的url
        foreach ($cate as $key=>&$value){
            $value['url']   =   'Article/index?cate_id='.$value['id'];
            if($cate_id == $value['id'] && $hide_cate){
                $value['current'] = true;
            }else{
                $value['current'] = false;
            }
            if(!empty($value['_child'])){
                $is_child = false;
                foreach ($value['_child'] as $ka=>&$va){
                    $va['url']      =   'Article/index?cate_id='.$va['id'];
                    if(!empty($va['_child'])){
                        foreach ($va['_child'] as $k=>&$v){
                            $v['url']   =   'Article/index?cate_id='.$v['id'];
                            $v['pid']   =   $va['id'];
                            $is_child = $v['id'] == $cate_id ? true : false;
                        }
                       
                    }
                     
                    //展开子分类的父分类
                    if($va['id'] == $cate_id || $is_child){
                        $is_child = false;
                        if($hide_cate){
                            $value['current']   =   true;
                            $va['current']      =   true;
                        }else{
                            $value['current'] 	= 	false;
                            $va['current']      =   false;
                        }
                    }else{
                        $va['current']      =   false;
                    }
                }
            }
        }
        $this->assign['nodes'] = $cate;
        $this->assign['cate_id']=    $this->cate_id;

        //获取面包屑信息
        $nav = get_parent_category($cate_id);
        $this->assign['rightNav']=   $nav;

        //获取回收站权限
        $show_recycle = $this->checkRule('Admin/article/recycle');
        $this->assign['show_recycle']= IS_ROOT || $show_recycle;
        //获取草稿箱权限
        $this->assign['show_draftbox']= C('OPEN_DRAFTBOX');
    }
    
    public function actionMydocument($status = null, $title = null)
    {
  
        
        //获取左边菜单
        $this->getMenu();

        $criteria=new CDbCriteria();
        $criteria->condition='uid='.UID;
        /* 查询条件初始化 */
        if(isset($title)){
            $criteria->addCondition("title like '%".$title."%'");
        }
        if(!is_null($status)){
            $criteria->addCondition('status='.$status);
        }else{
            $criteria->addInCondition('status', array('0','1','2'));
        }
        if ( isset($_GET['time-start']) ) {
            $criteria->addCondition('update_time>='.strtotime(get('time-start')));
        }
        if ( isset($_GET['time-end']) ) {
            $criteria->addCondition('update_time<='.(24*60*60+strtotime(get('time-end'))));
        }
        
        //只查询pid为0的文章
        $criteria->addCondition('pid=0');
        $criteria->order='update_time desc';
        $list = $this->lists('Document',$criteria);
        int_to_string($list);

        $this->render('mydocument',array('status'=>$status,'list'=>$list,'pages'=>$this->pages));
    }
    
    
    public function actionIndex($cate_id = null)
    {
        $this->resetAssign();
        //获取左边菜单
        $this->getMenu();

        if($cate_id===null){
            $cate_id = $this->cate_id;
        }

        //获取模型信息
        $model = DocModel::model()->findByAttributes(array('name'=>'document'));
   

        //解析列表规则
        $fields = array();
        $grids  = preg_split('/[;\r\n]+/s', $model['list_grid']);
        foreach ($grids as &$value) {
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            $field   = explode(',', $val[0]);
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']  =   $val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array  =   explode('|',$val);
                $fields[] = $array[0];
            }
        }

        // 过滤重复字段信息 TODO: 传入到查询方法
        $fields = array_unique($fields);

        //获取对应分类下的模型
        if(!empty($cate_id)){   //没有权限则不查询数据
            //获取分类绑定的模型
            $models         =   get_category($cate_id, 'model');
            $allow_reply    =   get_category($cate_id, 'reply');//分类文档允许回复
            $pid            =   gp('pid');
            if ( $pid==0 ) {
                //开发者可根据分类绑定的模型,按需定制分类文档列表
                $template = $this->indexOfArticle( $cate_id ); //转入默认文档列表方法
                $this->assign['model']=  explode(',',$models);
            }else{
                //开发者可根据父文档的模型类型,按需定制子文档列表
                $doc_model = Document::model()->find(
                    array('condition'=>'id='.$pid,)
                ); 

                switch($doc_model['model_id']){
                    default:
                        if($doc_model['type']==2 && $allow_reply){
                            $this->assign['model']=  array(2);
                            $template = $this->indexOfReply( $cate_id ); //转入子文档列表方法
                        }else{
                            $this->assign['model']=  explode(',',$models);
                            $template = $this->indexOfArticle( $cate_id ); //转入默认文档列表方法
                        }
                }
            }

            $this->assign['list_grids']=$grids;
            $this->assign['model_list']=$model;
            // 记录当前列表页的cookie
           
            $this->render($template);
        }else{
            $this->ajaxReturn('非法的文档分类');
        }
    }
    
    
 	/**
     * 默认文档列表方法
     * @param $cate_id 分类id
     */
    protected function indexOfArticle($cate_id){
        
        /* 查询条件初始化 */
        $criteria=new CDbCriteria();
        if(isset($_GET['title'])){
            $criteria->addCondition("title like '%".(string)gp('title')."%'");
        }
        
        if(isset($_GET['status'])){
            $criteria->addCondition("status = ".(int)gp('status'));
            $status = (int)gp('status');
        }else{
            $status = null;
            $criteria->addInCondition('status', array('0','1','2'));
        }
        if ( !isset($_GET['pid']) ) {
            $criteria->addCondition('pid=0');
        }
        if ( isset($_GET['time-start']) ) {
            $criteria->addCondition('update_time>='.strtotime(gp('time-start')));
        }
        if ( isset($_GET['time-end']) ) {
            $criteria->addCondition('update_time<='.(24*60*60+strtotime(gp('time-end'))));
        }
        if ( isset($_GET['nickname']) ) {
            $member=Member::model()->findByAttributes('nickname='.gp('nickname'));
            $criteria->addCondition('uid='.$member['uid']);
            
        }

        // 构建列表数据

        
        $pid=intval(gp('pid'));
        if(!$pid)
        {
             $criteria->addCondition('category_id='.$cate_id);
        }
        $criteria->order='level desc,id desc';
        $list = $this->lists('Document',$criteria);
        int_to_string($list);
        if($pid){
            // 获取上级文档
            $article    = Document::model()->findByPk($pid);
            $this->assign['article'] = $article;
        }
        //检查该分类是否允许发布内容
        $allow_publish  =   get_category($cate_id, 'allow_publish');

        $this->assign['status']= $status;
        $this->assign['list']=  $list;
        $this->assign['allow']=  $allow_publish;
        $this->assign['pid']=    $pid;
        $this->assign['pages']=$this->pages;
        return 'index';
    }
    //重置assign
    private function resetAssign()
    {
        $this->assign=array();
    }
    
    //添加
    public function actionAdd()
    {
        $cate_id    =(int)get('cate_id');
        $model_id   =(int)get('model_id');  
        if(IS_POST)
        {
            $document=new Document();
            $data=$_POST;
            $data['create_time']=strtotime($data['create_time']);
            $data['deadline']=strtotime($data['deadline']);
            $res = $document->update($data);
            if(!$res){
                $this->ajaxReturn($document->getMyError());
            }else{
                $this->ajaxReturn($res['id']?'更新成功':'新增成功', Yii::app()->request->urlReferrer);
            }
        }else {
            //获取左边菜单
            $this->getMenu();

            empty($cate_id) && $this->ajaxReturn('参数不能为空！');
            empty($model_id) && $this->ajaxReturn('该分类未绑定模型！');
    
            //检查该分类是否允许发布
            $allow_publish = Document::model()->checkCategory($cate_id);
            !$allow_publish && $this->ajaxReturn('该分类不允许发布内容！');
    
            /* 获取要编辑的扩展模型模板 */
            $model      =   get_document_model($model_id);
    
            //处理结果
            $info['pid']            =   $_GET['pid']?$_GET['pid']:0;
            $info['model_id']       =   $model_id;
            $info['category_id']    =   $cate_id;
            $article='';
            if($info['pid']){
                // 获取上级文档
                $article            =  Document::model()->findByPk($info['pid']);  
            }
    
            //获取表单字段排序
            $fields = get_model_attribute($model['id']);
            
            $this->render('add',array('article'=>$article,'info'=>$info,'fields'=>$fields,'type_list'=>get_type_bycate($cate_id),'model'=>$model));
        }
    }
    
    //编辑
    public function actionEdit()
    {
        if(IS_POST)
        {
            $document=new Document();
            $data=$_POST;
            $res = $document->update($data);
            if(!$res){
                $this->ajaxReturn($document->getMyError());
            }else{
                $this->ajaxReturn($res['id']?'更新成功':'新增成功', Yii::app()->request->urlReferrer);
            }
        }else 
        {
            //获取左边菜单
            $this->getMenu();
    
            $id     =   get('id');
            if(empty($id)){
                $this->ajaxReturn('参数不能为空！');
            }
    
            /*获取一条记录的详细数据*/
            $Document = new Document();
            $data = $Document->detail($id);
            if(!$data){
                $this->ajaxReturn($Document->getMyError());
            }
            $article=null;
            if($data['pid']){
                // 获取上级文档
                $article        =   Document::model()->findByPk($data['pid']);
                $article=findall_to_array($article);
               
            }
            
            /* 获取要编辑的扩展模型模板 */
            $model      =   get_document_model($data['model_id']);
            
            //获取表单字段排序
            $fields = get_model_attribute($model['id']);
            
    
            $this->render('edit',array('article'=>$article,'data'=>$data,'model_id'=>$data['model_id'],'model'=>$model,'fields'=>$fields,'type_list'=>get_type_bycate($data['category_id'])));
        }
    }
    //回收站
    public function actionRecycle()
    {
        //获取左边菜单
        $this->getMenu();
        $criteria=new CDbCriteria();
        $criteria->condition='status='.DELETE_VAL;
        if ( !IS_ROOT ) {
            $cate_auth  =   AuthGroup::getAuthCategories(UID);
            if($cate_auth){
                $criteria->addInCondition('category_id', $cate_auth);
            }else{
                $map['category_id']    =   -1;
                $criteria->addCondition('category_id=-1');
            }
        }
        $criteria->order='update_time desc';
        $list = $this->lists('Document',$criteria);

        //处理列表数据
        if(is_array($list)){
            foreach ($list as $k=>&$v){
                $v['username']      =   get_memberinfo($v['uid'],'nickname');
            }
        }
        $this->render('recycle',array('list'=>$list,'pages'=>$this->pages));
        
    }
    
    //清空回收站
    public function actionClear()
    {
        $document=new Document();
        $res = $document->remove();
        if($res !== false){
            $this->ajaxReturn('清空回收站成功！',url('article/recycle'));
        }else{
            $this->ajaxReturn('清空回收站失败！');
        }
    }
    
    //还原回收站
    public function actionPermit()
    {
        $this->actionSetStatus(RESUME_VAL,'Document','recycle');
    }
    
    //存草稿
    public function actionAutoSave()
    {
        $data=$_POST;
        $document=new Document();

        $res = $document->autoSave($data);
        if($res !== false){
            $return['data']     =   $res;
            $return['info']     =   '保存草稿成功';
            $return['status']   =   1;
            $this->ajaxReturn('保存草稿成功');
        }else{
            $this->ajaxReturn('保存草稿失败：'.$document->getMyError());
        }
    }
    
    //草稿箱
    public function actionDraftbox()
    {
         //获取左边菜单
        $this->getMenu();

        $criteria=new CDbCriteria();
        $criteria->condition='status='.DRAFT_VAL.' and uid='.UID;
        $list       =   $this->lists('Document',$criteria);
        //获取状态文字
        //int_to_string($list);

        $this->render('draftbox',array('list'=>$list,'pages'=>$this->pages));
    }
    
    //待审核
    public function actionExamine()
    {
         //获取左边菜单
        $this->getMenu();
        $criteria=new CDbCriteria();
        $criteria->condition='status='.PENDING_VAL;
        if ( !IS_ROOT ) {
            $cate_auth  =   AuthGroup::getAuthCategories(UID);
            if($cate_auth){
                $criteria->addInCondition('category_id', $cate_auth);
            }else{
                $map['category_id']    =   -1;
                $criteria->addCondition('category_id=-1');
            }
        }
        $criteria->order='update_time desc';
        $list = $this->lists('Document',$criteria);

        //处理列表数据
        if(is_array($list)){
            foreach ($list as $k=>&$v){
                $v['username']      =   get_memberinfo($v['uid'],'nickname');
            }
        }
        $this->render('examine',array('list'=>$list,'pages'=>$this->pages));
    }
    
    
    
    
    /**
     * 移动文档
     */
    public function actionMove() {
        if(empty($_POST['ids'])) {
            $this->ajaxReturn('请选择要移动的文档！');
        }
        setSession('moveArticle', $_POST['ids']);
        setSession('copyArticle', null);
        $this->ajaxReturn('请选择要移动到的分类！');
    }

    /**
     * 拷贝文档
     */
    public function actionCopy() {
        if(empty($_POST['ids'])) {
            $this->ajaxReturn('请选择要复制的文档！');
        }
        setSession('copyArticle', $_POST['ids']);
        setSession('moveArticle', null);
        $this->ajaxReturn('请选择要复制到的分类！');
    }
    
    
    
 /**
     * 粘贴文档
     */
    public function actionPaste() {
        $moveList = getSession('moveArticle');
        $copyList = getSession('copyArticle');
        if(empty($moveList) && empty($copyList)) {
            $this->ajaxReturn('没有选择文档！');
        }
        if(!isset($_POST['cate_id'])) {
            $this->ajaxReturn('请选择要粘贴到的分类！');
        }
        $cate_id = post('cate_id');	//当前分类
        $pid = (int)post('pid');		//当前父类数据id

        //检查所选择的数据是否符合粘贴要求
        $check = $this->checkPaste(empty($moveList) ? $copyList : $moveList, $cate_id, $pid);
        if(!$check['status']){
            $this->ajaxReturn($check['info']);
        }
        $res=false;
        if(!empty($moveList)) {// 移动	TODO:检查name重复
            foreach ($moveList as $key=>$value){
                $Model              =   new Document();
                $data['category_id']=   $cate_id;
                $data['pid'] 		=   $pid;
                //获取root
                if($pid == 0){
                    $data['root'] = 0;
                }else{
                    $p_root = Document::model()->findByPk($pid);
                    $p_root=$p_root->root;
                    $data['root'] = $p_root == 0 ? $pid : $p_root;
                }
                $res = $Model->updateByPk($value, $data);
            }
            setSession('moveArticle', null);
            if(false !== $res){
                $this->ajaxReturn('文档移动成功！',app()->request->urlReferrer);
            }else{
                $this->ajaxReturn('文档移动失败！');
            }
        }elseif(!empty($copyList)){ // 复制
            foreach ($copyList as $key=>$value){
                $Model  =   new Document();
                $data   =   $Model->findByPk($value);
                $data=findall_to_array($data);
                unset($data['id']);
                unset($data['name']);
                $data['category_id']    =   $cate_id;
                $data['pid'] 			=   $pid;
                $data['create_time']    =   NOW_TIME;
                $data['update_time']    =   NOW_TIME;
                //获取root
                if($pid == 0){
                    $data['root'] = 0;
                }else{
                    $p_root = Document::model()->findByPk($pid);
                    $p_root=$p_root->root;
                    $data['root'] = $p_root == 0 ? $pid : $p_root;
                }

                $Model->attributes=$data;

                $result   =  $Model->save();
                if($result){
                    $m=get_document_model($data['model_id'],'name');
                    $logic="Document".ucfirst($m);
                    $logic=new $logic;
                    $data_add       =   $logic->detail($value); //获取指定ID的扩展数据
                    $data_add=findall_to_array($data_add);
                    $data_add['id'] =   $Model->id;
                    $logic->attributes=$data_add;
                    $res 		= 	$logic->save();
                   /* if(!$res)
                    {
                       $this->ajaxReturn($this->get_model_ajax_error($logic)); 
                    }*/
                }/*else {
                    $this->ajaxReturn($this->get_model_ajax_error($Model));
                }*/
            }
            setSession('copyArticle', null);
            if($res){
                $this->ajaxReturn('文档复制成功！',app()->request->urlReferrer);
            }else{
                $this->ajaxReturn('文档复制失败！');
            }
        }
    }

    /**
     * 检查数据是否符合粘贴的要求
     */
    protected function checkPaste($list, $cate_id, $pid){
        $return = array('status'=>1);
        $Document = new Document();

        // 检查支持的文档模型
        $modelList =   Category::model()->findByPk($cate_id);	// 当前分类支持的文档模型
        $modelList=$modelList->model;
        foreach ($list as $key=>$value){
            //不能将自己粘贴为自己的子内容
            if($value == $pid){
                $return['status'] = 0;
                $return['info'] = '不能将编号为 '.$value.' 的数据粘贴为他的子内容！';
                return $return;
            }
            // 移动文档的所属文档模型
            $modelType  =   $Document->findByPk($value);
            $modelType=$modelType->model_id;
            if(!in_array($modelType,explode(',',$modelList))) {
                $return['status'] = 0;
                $return['info'] = '当前分类的文档模型不支持编号为 '.$value.' 的数据！';
                return $return;
            }
        }

        // 检查支持的文档类型和层级规则
        $typeList = Category::model()->findByPk($cate_id);   	// 当前分类支持的文档模型
        $typeList=$typeList->type;
        foreach ($list as $key=>$value){
            // 移动文档的所属文档模型
            $modelType  =   $Document->findByPk($value);
            $modelType=$modelType->type;
            if(!in_array($modelType,explode(',',$typeList))) {
                $return['status'] = 0;
                $return['info'] = '当前分类的文档类型不支持编号为 '.$value.' 的数据！';
                return $return;
            }
            $res = $Document->checkDocumentType($modelType, $pid);
            if(!$res['status']){
                $return['status'] = 0;
                $return['info'] = $res['info'].'。错误数据编号：'.$value;
                return $return;
            }
        }

        return $return;
    }
    
    //排序
    public function actionSort()
    {
        if(IS_GET){
            //获取左边菜单
            $this->getMenu();

            $ids        =   get('ids');
            $cate_id    =   get('cate_id');
            $pid        =   get('pid');
            $criteria=new CDbCriteria();
            $criteria->condition='status>'.DELETE_VAL;
            //获取排序的数据
           
            if(!empty($ids)){
                $criteria->addInCondition('id', $ids);
            }else{
                if($cate_id !== ''){

                    $criteria->addCondition('category_id='.$cate_id);
                }
                if($pid !== ''){
                    $criteria->addCondition('pid='.$pid);
                }
            }
            $criteria->select='id,title';
            $criteria->order='level DESC,id DESC';
            $list = Document::model()->findAll($criteria);
            $list=findall_to_array($list);
            $this->render('sort',array('list'=>$list));
        }elseif (IS_POST){
            $ids = post('ids');
            $ids = array_reverse(explode(',', $ids));
            foreach ($ids as $key=>$value){
                $data['level']=$key+1;
                $res = Document::model()->updateByPk($value, $data);
            }
            if($res !== false){
                $this->ajaxReturn('排序成功！',app()->request->urlReferrer);
            }else{
                $this->ajaxReturn('排序失败！');
            }
        }else{
            $this->ajaxReturn('非法请求！');
        }
    }
}