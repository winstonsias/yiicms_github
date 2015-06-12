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
**********examine.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-21**********
*/
?>
<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>待审核(<?php echo count($list)?>)</h2>
	</div>

    <div class="tools auth-botton">
     	<button class="btn ajax-post confirm" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>DELETE_VAL,'action'=>'examine','model'=>'Document'))?>">删 除</button>
		    <button url="<?php echo url('article/setStatus',array('status'=>RESUME_VAL,'action'=>'examine','model'=>'Document'))?>" class="btn ajax-post" target-form="ids">审 核</button>
    </div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
			<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标题</th>
		<th class="">创建者</th>
		<th class="">类型</th>
		<th class="">分类</th>
		<th class="">发布时间</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
    <?php foreach ($list as $vo){?>


		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo $vo['id']?>" /></td>
			<td><?php echo $vo['id']?></td>
			<td><a data-id="<?php echo $vo['id']?>" href="<?php echo url('article/edit',array('cate_id'=>$vo['category_id'],'id'=>$vo['id']))?>"><?php echo $vo['title']?></a></td>
			
			<td><?php echo $vo['username']?></td>
			<td><?php echo get_document_type($vo['type'])?></td>
			<td><span><?php echo get_cate($vo['category_id'])?></span></td>
			<td><span><?php echo date("Y-m-d H:i",$vo['create_time'])?></span></td>
			<td><a href="<?php echo url('article/edit',array('cate_id'=>$vo['category_id'],'id'=>$vo['id']))?>">编辑</a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>RESUME_VAL,'action'=>'examine','model'=>'Document'))?>" class="ajax-get">审核</a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>DELETE_VAL,'action'=>'examine','model'=>'Document'))?>" class="confirm ajax-get">删除</a>
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