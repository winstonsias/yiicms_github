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
**********index.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-3**********
*/
?>

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>权限管理</h2>
	</div>

    <div class="tools auth-botton">
        <a id="add-group" class="btn" href="<?php echo url('AuthManager/createGroup')?>">新 增</a>
        <a url="<?php echo url('AuthManager/changeStatus',array('method'=>'resumeGroup'))?>" class="btn ajax-post" target-form="ids" >启 用</a>
        <a url="<?php echo url('AuthManager/changeStatus',array('method'=>'forbidGroup'))?>" class="btn ajax-post" target-form="ids" >禁 用</a>
        <a url="<?php echo url('AuthManager/changeStatus',array('method'=>'deleteGroup'))?>" class="btn ajax-post confirm" target-form="ids" >删 除</a>
    </div>
	<!-- 数据列表 -->
	<div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">用户组</th>
		<th class="">描述</th>

		<th class="">授权</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
<?php foreach ($lists as $vo){?>
		<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo $vo['id']?>" /></td>
			<td><a href="<?php echo url('AuthManager/editgroup',array('id'=>$vo['id']))?>"><?php echo $vo['title']?></a> </td>
			<td><span><?php echo truncate_utf8_string($vo['description'],60)?></span></td>


			<td><a href="<?php echo url('AuthManager/access',array('group_name'=>$vo['title'],'group_id'=>$vo['id']))?>" >访问授权</a>
			<a href="<?php echo url('AuthManager/category',array('group_name'=>$vo['title'],'group_id'=>$vo['id']))?>" >分类授权</a>
			<a href="<?php echo url('AuthManager/user',array('group_name'=>$vo['title'],'group_id'=>$vo['id']))?>" >成员授权</a>
			</td>
			<td><?php echo $vo['status_text']?></td>
			<td>
			<?php if($vo['status']==1){?>
			
			<a href="<?php echo url('AuthManager/changeStatus',array('method'=>'forbidGroup','id'=>$vo['id']));?>" class="ajax-get">禁用</a>
			<?php }else{?>
			<a href="<?php echo url('AuthManager/changeStatus',array('method'=>'resumeGroup','id'=>$vo['id']));?>" class="ajax-get">启用</a>
			<?php }?>
			
				<a href="<?php echo url('AuthManager/changeStatus',array('method'=>'deleteGroup','id'=>$vo['id']));?>" class="confirm ajax-get">删除</a>
                </td>
		</tr>
		<?php }?>
	</tbody>
    </table>

	</div>
    <div class="page">
         <?php 
 //分页widget代码: 
 $this->widget('CLinkPager',array(   //此处Yii内置的是CLinkPager，我继承了CLinkPager并重写了相关方法
    'header'=>'',
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'pages' => $pages,       
    'maxButtonCount'=>10,    //分页数目
    'htmlOptions'=>array(
       'class'=>'paging',   //包含分页链接的div的class
     )
  ));
 ?>
    </div>
</block>

<block name="script">
<script type="text/javascript" charset="utf-8">
    //导航高亮
highlight_subnav("<?php echo url('AuthManager/index')?>");
</script>
</block>
