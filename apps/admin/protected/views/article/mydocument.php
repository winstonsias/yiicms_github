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
**********mydocument.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-13**********
*/
?>
<block name="body">
	<!-- 标题 -->
	<div class="main-title">
		<h2>
		我的文档(<?php echo $this->total?>)
		</h2>
	</div>

	<!-- 按钮工具栏 -->
	<div class="cf">
		<div class="fl">
            <button class="btn ajax-post" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>1,'action'=>'mydocument','model'=>'Document'))?>">启 用</button>
			<button class="btn ajax-post" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>0,'action'=>'mydocument','model'=>'Document'))?>">禁 用</button>
			<button class="btn ajax-post confirm" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>-1,'action'=>'mydocument','model'=>'Document'))?>">删 除</button>
		</div>

		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<div class="drop-down">
					<span id="sch-sort-txt" class="sort-txt" data="<?php echo $status?>">
					
					<?php if(get_status_title($status)==''){echo '所有';} else {echo get_status_title($status);}?>
					</span>
					<i class="arrow arrow-down"></i>
					<ul id="sub-sch-menu" class="nav-list hidden">
						<li><a href="javascript:;" value="">所有</a></li>
						<li><a href="javascript:;" value="1">正常</a></li>
						<li><a href="javascript:;" value="0">禁用</a></li>
						<li><a href="javascript:;" value="2">待审核</a></li>
					</ul>
				</div>
				<input type="text" name="title" class="search-input" value="<?php echo gp('title')?>" placeholder="请输入标题文档">
				<a class="sch-btn" href="javascript:;" id="search" url="<?php echo url('article/mydocument')
				//array('pid'=>gp('pid')?gp('pid'):0,
				//'cate_id'=>$this->assign['cate_id'],?>"><i class="btn-search"></i></a>
			</div>
            <div class="btn-group-click adv-sch-pannel fl">
                <button class="btn">高 级<i class="btn-arrowdown"></i></button>
                <div class="dropdown cf">
                	<div class="row">
                		<label>创建时间：</label>
                		<input type="text" id="time-start" name="time-start" class="text input-2x" value="" placeholder="起始时间" /> -                		
                        <div class="input-append date" id="datetimepicker"  style="display:inline-block">
                            <input type="text" id="time-end" name="time-end" class="text input-2x" value="" placeholder="结束时间" />
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                	</div>
                </div>
            </div>
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
		<th class="">子文档</th>
		<th class="">类型</th>
		<th class="">分类</th>
		<th class="">优先级</th>
		<th class="">最后更新</th>
		<th class="">浏览</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
    <?php foreach ($list as $vo){?>

		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="<?php echo $vo['id']?>" /></td>
			<td><?php echo $vo['id']?> </td>
			<td><a href="<?php echo url('article/index',array('cate_id'=>$vo['category_id'],'pid'=>$vo['id']))?>"><?php echo $vo['title']?></a></td>
			<td><span><?php echo get_subdocument_count($vo['id'])?></span></td>
			<td><span><?php echo get_document_type($vo['type'])?></span></td>
			<td><span><?php echo get_cate($vo['category_id'])?></span></td>
			<td><?php echo $vo['level']?></td>
			<td><span><?php echo date('Y-m-d H:i',$vo['update_time'])?></span></td>
			<td><?php echo $vo['view']?></td>
			<td><?php echo $vo['status_text']?></td>
			<td><a href="<?php echo url('article/edit',array('cate_id'=>$vo['category_id'],'id'=>$vo['id']))?>">编辑</a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>abs(1-$vo['status']),'action'=>'mydocument','model'=>'Document'))?>" class="ajax-get"><?php echo show_status_op($vo['status'])?></a>
				<a href="<?php echo url('article/setStatus',array('ids'=>$vo['id'],'status'=>-1,'action'=>'mydocument','model'=>'Document'))?>" class="confirm ajax-get">删除</a>
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
<script type="text/javascript">
$(function(){
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
		var status = $("#sch-sort-txt").attr("data");
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
       
		if(status != ''){
			query += '&status=' + status + "&" + query;
        }
		 query +='&pid=<?php echo gp('pid')?gp('pid'):0?>';
 
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});


	/* 状态搜索子菜单 */
	$(".search-form").find(".drop-down").hover(function(){
		$("#sub-sch-menu").removeClass("hidden");
	},function(){
		$("#sub-sch-menu").addClass("hidden");
	});
	$("#sub-sch-menu li").find("a").each(function(){
		$(this).click(function(){
			var text = $(this).text();
			$("#sch-sort-txt").text(text).attr("data",$(this).attr("value"));
			$("#sub-sch-menu").addClass("hidden");
		})
	});

    //回车自动提交
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });

    $('#time-start').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
	    minView:2,
	    autoclose:true
    });

    $('#datetimepicker').datetimepicker({
       format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true,
        pickerPosition:'bottom-left'
    })
    
})
</script>
</block>
