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
**********DATE:2014-11-27**********
*/
?>

	<!-- 标题栏 -->
	<div class="main-title">
		<h2>[<?php echo get_model_by_id($model_id)?>] 属性列表(不含继承属性)</h2>

	</div>
    <div class="tools">
        <a class="btn" href="<?php echo url('attribute/add',array('model_id'=>$model_id))?>">新 增</a>
    </div>

	<!-- 数据列表 -->
	<div class="data-table">
        <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">字段</th>
		<th class="">名称</th>
		<th class="">数据类型</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
<?php foreach ($_list as $vo){?>

		<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo $vo['id']?>" /></td>
			<td><?php echo $vo['id']?> </td>
			<td><?php echo $vo['name']?></td>
			<td><a data-id="<?php echo $vo['id']?>" href="<?php echo url('attribute/edit',array('id'=>$vo['id']))?>"><?php echo $vo['title']?></a></td>
			<td><span><?php echo get_attribute_type($vo['type'])?></span></td>
			<td><a href="<?php echo url('attribute/edit',array('id'=>$vo['id']))?>">编辑</a>
				<a class="confirm ajax-get" href="<?php echo url('attribute/delete',array('id'=>$vo['id']))?>">删除</a>
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
    'pages' => $this->pages,       
    'maxButtonCount'=>10,    //分页数目
    'htmlOptions'=>array(
       'class'=>'paging',   //包含分页链接的div的class
     )
  ));
 ?>
    </div>



    <script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">
  	//导航高亮
    highlight_subnav('<?php echo url('Model/index')?>');
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

