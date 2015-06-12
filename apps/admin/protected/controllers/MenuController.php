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
**********MenuController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-5**********
*/
class MenuController extends BackendBaseController
{
    public function actionIndex()
    {
        $pid  = get('pid')?get('pid'):0;
        $data=array();
        if($pid){
            $data = Menu::model()->find(
                array(
                    'condition'=>"id=".$pid,
                )
            );
            
            
        }
        $title      =   trim(get('title'));
        $type       =   C('CONFIG_GROUP_LIST');
        $all_menu   =   Menu::model()->findAll(
            array(
                'select'=>'id,title',
            )
        );
        $all_menu=findall_field_to_array($all_menu,'id,title');
        $addcondition="";
        if($title)
            $addcondition = " and title like '%".$title."%' ";
        $list       =  Menu::model()->findAll(
            array(
                'condition'=>"pid=".$pid.$addcondition,
                'order'=>'sort asc,id asc'
            )
        ); 
        $list=findall_to_array($list);
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
        }
        // 记录当前列表页的cookie
        $cookie = new CHttpCookie('__forward__',$_SERVER['REQUEST_URI']);
        $cookie->expire = time()+60*60*24*30;  //有限期30天
        Yii::app()->request->cookies['__forward__']=$cookie;
        $this->render('index',array('list'=>$list,'data'=>$data));
    }
    
    public function actionAdd()
    {
       if (IS_POST){
            $Menu = new Menu();
            $Menu->attributes=$_POST;
            $data=$Menu->validate();
            if($data){
                $id = $Menu->save();
                if($id){
                    $cookie = Yii::app()->request->getCookies();
                    $this->ajaxReturn('新增成功', $cookie['__forward__']->value);
                } else {
                    $this->ajaxReturn('新增失败');
                }
            } else {
                $this->ajaxReturn($this->get_model_ajax_error($Menu));
            }
        } else {
            $menus = Menu::model()->findAll();
            $menus=findall_to_array($menus);
            $tree=new Tree();
            $menus = $tree->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->render('edit',array('info'=>array('pid'=>gp('pid')),'menus'=>$menus));
        }
    }
    //修改
     public function actionEdit($id = 0){
         $id=$id?$id:post('id');
        if(IS_POST){
            $Menu = Menu::model()->findByPk($id);
            $Menu->attributes=$_POST;
            $data=$Menu->validate();
            if($data){
                if($Menu->save()!== false){
                    $cookie = Yii::app()->request->getCookies();
                    $this->ajaxReturn('更新成功', $cookie['__forward__']->value);
                } else {
                    $this->ajaxReturn('更新失败');
                }
            } else {
                $this->ajaxReturn($this->get_model_ajax_error($Menu));
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = Menu::model()->findByPk($id);
            $info=findall_to_array($info);

            $menus = Menu::model()->findAll();
            $menus=findall_to_array($menus);
            $tree=new Tree();
            $menus = $tree->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            if(false === $info){
                $this->ajaxReturn('获取后台菜单信息错误');
            }

            $this->render('edit',array('info'=>$info,'menus'=>$menus));
        }
    }
    
    //删除
    public function actionDelete()
    {
        $id=gp('id')?gp('id'):'';
        if ( empty($id) ) {
            $this->ajaxReturn('请选择要操作的数据!');
        }
        $id = array_unique((array)$id);
        $id=implode(',', $id);
        $haschild=Menu::model()->findAll(
            array('condition'=>"pid in ($id)",)
        );
        if($haschild)
        {
            $this->ajaxReturn('选中的分类有子分类,请先删除子分类');
        }
        if(Menu::model()->deleteAll("id in ($id)")){
            $this->ajaxReturn('删除成功',url('menu/index'));
        } else {
            $this->ajaxReturn('删除失败！');
        }
    }
    //排序
    public function actionSort()
    {
         if(!IS_POST){
            $ids = get('ids');
            $pid = get('pid');

            //获取排序的数据
            $addcondition=" `hide`>-1 ";
            if(!empty($ids)){
                $addcondition.=" and id in ($ids)";
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                    $addcondition.=' and pid='.$pid;
                }
            }
            $list = Menu::model()->findAll(
                array(
                    'condition'=>$addcondition,
                    'select'=>'id,title',
                    'order'=>'sort asc,id asc',
                )
            );
         $cookie = Yii::app()->request->getCookies();
            $this->render('sort',array('list'=>$list,'forward'=>$cookie['__forward__']->value));
        }elseif (IS_POST){
            $ids = post('ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $menu=Menu::model()->findByPk($value);
                $menu->sort=$key+1;
                $res=$menu->save();
            }
            if($res !== false){
                $this->ajaxReturn('排序成功！','',1);
            }else{
                $this->ajaxReturn('排序失败！');
            }
        }else{
            $this->ajaxReturn('非法请求！');
        }
    }
    //更改状态
    public function actionChangestatus($id,$value,$type)
    {
        if(!$id)
        {
            $this->ajaxReturn('参数错误！');
        }
        $menu=Menu::model()->findByPk($id);
        switch ($type)
        {
            case 'dev':
                $menu->is_dev=$value;
                break;
            case 'hide':
                $menu->hide=$value;
                break;
        }
        if($menu->save())
        {
            $this->ajaxReturn('更新状态成功！',url('menu/index'));
        }else {
            $this->ajaxReturn('更新状态失败！');
        }
    }
    //导入
    public function actionImport(){
        if(IS_POST){
            $tree = gp('tree');
            $lists = explode(PHP_EOL, $tree);
            
            if($lists == array()){
                $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            }else{
                $pid = gp('pid');
                foreach ($lists as $key => $value) {
                    $menuModel = new Menu();
                    $record = explode('|', $value);
                    if(count($record) == 2){
                        
                        $menuModel->title=$record[0];
                        $menuModel->url=$record[1];
                        $menuModel->pid=$pid;
                        $menuModel->sort=0;
                        $menuModel->hide=0;
                        $menuModel->tip='';
                        $menuModel->is_dev=0;
                        $menuModel->group='';
                       
                    }
                    $menuModel->save();
                }
                $this->ajaxReturn('导入成功',url('menu/index',array('pid'=>$pid)));
            }
        }else{
            $pid = (int)gp('pid');;
            $data = Menu::model()->find("id=".$pid);
            $this->render('import',array('data'=>$data,'pid'=>$pid));
        }
    }
}