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
**********DATE:2014-11-21**********
*/
?>

	<!-- 标题栏 -->
	<div class="main-title">
		<h2>模型列表</h2>

	</div>
    <div class="tools">
        <a class="btn" href="<?php echo url('model/add')?>">新 增</a>
        <button class="btn ajax-post" target-form="ids" url="<?php echo url('model/setStatus',array('status'=>RESUME_VAL,'model'=>'DocModel'))?>">启 用</button>
        <button class="btn ajax-post" target-form="ids" url="<?php echo url('model/setStatus',array('status'=>FORBID_VAL,'model'=>'DocModel'))?>">禁 用</button>
        <a class="btn" href="<?php echo url('model/generate')?>">生 成</a>
    </div>

	<!-- 数据列表 -->
	<div class="data-table">
        <div class="data-table table-striped">
<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标识</th>
		<th class="">名称</th>
		<th class="">创建时间</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach ($list as $vo){?>

		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo $vo['id']?>" /></td>
			<td><?php echo $vo['id']?> </td>
			<td><?php echo $vo['name']?></td>
			<td><a data-id="<?php echo $vo['id']?>" href="<?php echo url('model/edit',array('id'=>$vo['id']))?>"><?php echo $vo['title']?></a></td>
			<td><span><?php echo date('Y-m-d H:i:s',$vo['create_time'])?></span></td>
			<td><?php echo $vo['status_text']?></td>
			<td>
				<a href="<?php echo url('model/lists',array('model'=>$vo['name']))?>">数据</a>
				<a href="<?php echo url('model/setStatus',array('ids'=>$vo['id'],'status'=>abs(1-$vo['status']),'model'=>'DocModel'))?>" class="ajax-get"><?php echo show_status_op($vo['status'])?></a>
				<a href="<?php echo url('model/edit',array('id'=>$vo['id']))?>">编辑</a>
				<a href="<?php echo url('model/delete',array('ids'=>$vo['id']))?>" class="confirm ajax-get">删除</a>
            </td>
		</tr>

		<?php }?>
	</tbody>
    </table>

        </div>
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


    <script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">
    $(function(){
    	$("#search").click(function(){
    		var url = $(this).attr('url');
    		var status = $('select[name=status]').val();
    		var search = $('input[name=search]').val();
    		if(status != ''){
    			url += '/status/' + status;
    		}
    		if(search != ''){
    			url += '/search/' + search;
    		}
    		window.location.href = url;
    	});
})
</script>
