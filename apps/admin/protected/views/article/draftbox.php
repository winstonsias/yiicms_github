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
**********draftbox.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-19**********
*/
?>

<block name="body">
	<!-- 标题 -->
	<div class="main-title">
		<h2>
		草稿箱(<?php echo count($list)?>)
		</h2>
	</div>

	<!-- 按钮工具栏 -->
	<div class="cf">
		<div class="fl">
			<button class="btn ajax-post confirm" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>-1,'action'=>'draftbox','model'=>'Document'))?>">删 除</button>
		</div>

		<!-- 高级搜索 -->
		<div class="search-form fr cf">
		</div>
	</div>


	<!-- 数据表格 -->
    <div class="data-table">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标题</th>
		<th class="">类型</th>
		<th class="">分类</th>
		<th class="">最后更新</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
    <?php foreach ($list as $vo){?>

		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo $vo['id']?>" /></td>
			<td><?php echo $vo['id']?></td>
			<td><a data-id="<?php echo $vo['id']?>" href="<?php echo url('article/edit',array('cate_id'=>$vo['category_id'],'id'=>$vo['id']))?>"><?php echo $vo['title']?></a></td>
			<td><?php echo get_document_type($vo['type'])?></td>
			<td><span><?php echo get_cate($vo['category_id'])?></span></td>
			<td><span><?php echo date("Y-m-d H:i",$vo['update_time'])?></span></td>
			<td><a href="<?php echo url('article/edit',array('cate_id'=>$vo['category_id'],'id'=>$vo['id']))?>">编辑</a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>RESUME_VAL,'model'=>'Document','action'=>'draftbox'))?>" class="ajax-get">启用</a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>DELETE_VAL,'model'=>'Document','action'=>'draftbox'))?>" class="confirm ajax-get">删除</a>
                </td>
                </tr>

		<?php }?>
	</tbody>
    </table> 
        	</div>

	<!-- 分页 -->
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
</div>

</block>
<block name="script">
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color'){
    echo '<link href=" '.Yii::app()->params['main_params']['static_url'] .'/extendstatic/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">';
}
?>
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

</block>