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
**********DATE:2014-11-12**********
*/
?>
<block name="body">
	<!-- 标题 -->
	<div class="main-title">
		<h2>
		文档列表(<?php echo $this->total?>) <?php if(is_array($this->assign['rightNav'])){?>[
		<?php $i=0; foreach ($this->assign['rightNav'] as $nav){ $i++;?>

		<a href="<?php echo url('article/index',array('cate_id'=>$nav['id']))?>"><?php echo $nav['title']?></a>
			
			<?php if(count($this->assign['rightNav'])>$i){echo '<i class="ca"></i>';}?>

		<?php }?>
		]
		<?php }?>

		<?php if($this->assign['allow']==0){?>（该分类不允许发布内容）<?php }?>
		</h2>
	</div>

	<!-- 按钮工具栏 -->
	<div class="cf">
		<div class="fl">
			<div class="btn-group">
			<?php if($this->assign['allow']>0){?>

					<button class="btn document_add" <?php if(count($this->assign['model'])==1){?>
					url="<?php echo url('article/add',array('cate_id'=>gp('cate_id'),'pid'=>(int)gp('pid'),'model_id'=>$this->assign['model'][0]))?>"
					<?php }?>
					
					>新 增
						<?php if(count($this->assign['model'])>1){?><i class="btn-arrowdown"></i><?php }?>
					</button>
					<?php if(count($this->assign['model'])>1){?>
					<ul class="dropdown nav-list">
					<?php foreach ($this->assign['model'] as $vo){?>
						
						<li>
						<a href="<?php echo url('article/add',array('cate_id'=>gp('cate_id'),'model_id'=>$vo,'pid'=>(int)gp('pid')))?>"><?php echo get_document_model($vo,'title')?>
						</a></li>
						
						<?php }?>
					</ul>

					<?php }?>
            <?php }else{?>
					<button class="btn disabled" >新 增
					<?php if(count($this->assign['model'])>1){?>
						<i class="btn-arrowdown"></i>
						<?php }?>
					</button>
					<?php }?>

				
			</div>
            <button class="btn ajax-post" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>1,'action'=>'index','model'=>'Document','param'=>serialize(array('cate_id'=>gp('cate_id')))))?>">启 用</button>
			<button class="btn ajax-post" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>0,'action'=>'index','model'=>'Document','param'=>serialize(array('cate_id'=>gp('cate_id')))))?>">禁 用</button>
			<button class="btn ajax-post" target-form="ids" url="<?php echo url('article/move')?>">移 动</button>
			<button class="btn ajax-post" target-form="ids" url="<?php echo url('article/copy')?>">复 制</button>
			<button class="btn ajax-post" target-form="ids" hide-data="true" url="<?php echo url('article/paste')?>">粘 贴</button>
			<input type="hidden" class="hide-data" name="cate_id" value="<?php echo gp('cate_id')?>"/>
			<input type="hidden" class="hide-data" name="pid" value="<?php echo (int)gp('pid')?>"/>
			<button class="btn ajax-post confirm" target-form="ids" url="<?php echo url('article/setStatus',array('status'=>-1,'action'=>'index','model'=>'Document','param'=>serialize(array('cate_id'=>gp('cate_id')))))?>">删 除</button>
			<!-- <button class="btn document_add" url="{:U('article/batchOperate',array('cate_id'=>$cate_id,'pid'=>I('pid',0)))}">导入</button> -->
			<button class="btn list_sort" url="<?php echo url('article/sort',array('cate_id'=>gp('cate_id'),'pid'=>(int)gp('pid')))?>">排序</button>
		</div>

		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<div class="drop-down">
					<span id="sch-sort-txt" class="sort-txt" data="<?php echo $this->assign['status']?>">
					
					<?php if(get_status_title($this->assign['status'])==''){echo '所有';} else {echo get_status_title($this->assign['status']);}?>
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
				<a class="sch-btn" href="javascript:;" id="search" url="<?php echo url('article/index')
				//array('pid'=>gp('pid')?gp('pid'):0,
				//'cate_id'=>$this->assign['cate_id'],?>"><i class="btn-search"></i></a>
			</div>
            <div class="btn-group-click adv-sch-pannel fl">
                <button class="btn">高 级<i class="btn-arrowdown"></i></button>
                <div class="dropdown cf">
                	<div class="row">
                		<label>更新时间：</label>
                		<input type="text" id="time-start" name="time-start" class="text input-2x" value="" placeholder="起始时间" /> -
                		<input type="text" id="time-end" name="time-end" class="text input-2x" value="" placeholder="结束时间" />
                	</div>
                	<div class="row">
                		<label>创建者：</label>
                		<input type="text" name="nickname" class="text input-2x" value="" placeholder="请输入用户名">
                	</div>
                </div>
            </div>
		</div>
	</div>

	<!-- 数据表格 -->
    <div class="data-table">
		<table>
            <!-- 表头 -->
            <thead>
                <tr>
                    <th class="row-selected row-selected">
                        <input class="check-all" type="checkbox">
                    </th>
                    <?php foreach ($this->assign['list_grids'] as $field){?>

                        <th><?php echo $field['title']?></th>

                    <?php }?>
                </tr>
            </thead>

            <!-- 列表 -->
            <tbody>
            <?php foreach ($this->assign['list'] as $data){?>

                    <tr>
                        <td><input class="ids" type="checkbox" value="<?php echo $data['id']?>" name="ids[]"></td>
                        <?php foreach ($this->assign['list_grids'] as $grid){?>

                            <td><?php echo get_list_field($data,$grid,$this->assign['model_list'])?></td>

                        <?php }?>
                    </tr>

                <?php } ?>
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
    'pages' => $this->assign['pages'],       
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
		 query +='&pid=<?php echo intval(gp('pid'))?>';
		 query +='&cate_id=<?php echo gp('cate_id')?>';
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

	//只有一个模型时，点击新增
	$('.document_add').click(function(){
		var url = $(this).attr('url');
		if(url != undefined && url != ''){
			window.location.href = url;
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

    $('#time-end').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
	    minView:2,
	    autoclose:true
    });
})
</script>
</block>

