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
**********DATE:2014-12-1**********
*/
?>

<div class="main-title">
		<h2>配置管理 [
		<?php if(isset($_GET['group'])){?>
		<a href="<?php echo url('config/index')?>">全部</a>
		<?php }else{?>
		<strong>全部</strong>
		<?php }?>
		 &nbsp;
		 <?php foreach ($group as $key=>$vo){?>
		<?php if($group_id!=$key){?>
		<a href="<?php echo url('config/index',array('group'=>$key))?>"><?php echo $vo?></a>
		<?php }else{?>
		<strong><?php echo $vo?></strong>
		<?php }?>
		&nbsp;     
         <?php }?>
       ]</h2>
	</div>

	<div class="cf">
		<a class="btn" href="<?php echo url('config/add')?>">新 增</a>
		<a class="btn ajax-post confirm" url="<?php echo url('config/delete')?>" target-form="ids">删 除</a>
		<button class="btn list_sort" url="<?php echo url('config/sort',array('group'=>intval(gp('group'))))?>">排序</button>
        
		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<input type="text" name="name" class="search-input" value="<?php echo gp('name')?>" placeholder="请输入配置名称">
				<a class="sch-btn" href="javascript:;" id="search" url="<?php echo url('config/index')?>"><i class="btn-search"></i></a>
			</div>
		</div>
	</div>

	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th class="row-selected">
						<input class="checkbox check-all" type="checkbox">
					</th>
					<th>ID</th>
					<th>名称</th>
					<th>标题</th>
					<th>分组</th>
					<th>类型</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $config){?>

					<tr>
						<td><input class="ids row-selected" type="checkbox" name="id[]" value="<?php echo $config['id']?>"></td>
						<td><?php echo $config['id']?></td>
						<td><a href="<?php echo url('config/edit',array('id'=>$config['id']))?>"><?php echo $config['name']?></a></td>
						<td><?php echo $config['title']?></td>
						<td><?php echo get_config_group($config['group'])?></td>
						<td><?php echo get_config_type($config['type'])?></td>
						<td>
							<a title="编辑" href="<?php echo url('config/edit',array('id'=>$config['id']))?>">编辑</a>
							<a class="confirm ajax-get" title="删除" href="<?php echo url('config/delete',array('id'=>$config['id']))?>">删除</a>
						</td>
					</tr>

			<?php }?>
			</tbody>
		</table>
		<!-- 分页 -->
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
	</div>



<script type="text/javascript">
//导航高亮
highlight_subnav('<?php echo url('Config/index')?>');
$(function(){
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
			$("#search").click();
			return false;
		}
	});
	//点击排序
	$('.list_sort').click(function(){
		var url = $(this).attr('url');
		var ids = $('.ids:checked');
		var param = '';
		if(ids.length > 0){
			var str = new Array();
			ids.each(function(){
				str.push($(this).val());
			});
			param = str.join(',');
		}

		if(url != undefined && url != ''){
			window.location.href = url + '/ids/' + param;
		}
	});
});
</script>