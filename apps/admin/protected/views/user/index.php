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
**********DATE:2014-10-29**********
*/
?>

	<!-- 标题栏 -->
	<div class="main-title">
		<h2>用户列表</h2>
	</div>
	<div class="cf">
		<div class="fl">
            <a class="btn" href="<?php echo url('user/add')?>">新 增</a>
            <button class="btn ajax-post" url="<?php echo url('User/changeStatus',array('method'=>'resumeUser'))?>" target-form="ids">启 用</button>
            <button class="btn ajax-post" url="<?php echo url('User/changeStatus',array('method'=>'forbidUser'))?>" target-form="ids">禁 用</button>
            <button class="btn ajax-post confirm" url="<?php echo url('User/changeStatus',array('method'=>'deleteUser'))?>" target-form="ids">删 除</button>
        </div>

        <!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<input type="text" name="nickname" class="search-input" value="<?php echo get('nickname');?>" placeholder="请输入用户昵称或者ID">
				<a class="sch-btn" href="javascript:;" id="search" url="<?php echo url('user/index')?>"><i class="btn-search"></i></a>
			</div>
		</div>
    </div>
    <!-- 数据列表 -->
    <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">UID</th>
		<th class="">昵称</th>
		<th class="">积分</th>
		<th class="">登录次数</th>
		<th class="">最后登录时间</th>
		<th class="">最后登录IP</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<?php foreach ($lists as $row):?>
		<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="<?php echo $row['uid']?>" /></td>
			<td><?php echo $row['uid']?> </td>
			<td><?php echo $row['nickname']?></td>
			<td><?php echo $row['score']?></td>
			<td><?php echo $row['login']?></td>
			<td><span><?php echo date("Y-m-d H:i:s",$row['last_login_time'])?></span></td>
			<td><span><?php echo $row['last_login_ip']?></span></td>
			<td><?php echo $row['status_text']?></td>
			<td>
			<?php if($row['status']==1){?>
			
			<a href="<?php echo url('User/changeStatus',array('method'=>'forbidUser','id'=>$row['uid']));?>" class="ajax-get">禁用</a>
			<?php }else{?>
			<a href="<?php echo url('User/changeStatus',array('method'=>'resumeUser','id'=>$row['uid']));?>" class="ajax-get">启用</a>
			<?php }?>
			
				<a href="<?php echo url('User/changeStatus',array('method'=>'deleteUser','id'=>$row['uid']));?>" class="confirm ajax-get">删除</a>
                </td>
		</tr>
		<?php endforeach;?>
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

	<script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/thinkbox/jquery.thinkbox.js"></script>

	<script type="text/javascript">
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
    //导航高亮
    highlight_subnav('<?php echo url('user/index')?>');
	</script>
