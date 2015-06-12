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
**********hooks.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-7**********
*/
?>
<div class="main-title">
		<h2>钩子列表</h2>
	</div>
	<div class="cf">
		<?php if (IS_ROOT){ ?>
			<a class="btn" href="<?php echo url('Addons/addhook')?>">新 增</a>
		<?php } ?>
	</div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>名称</th>
					<th>描述</th>
					<th>类型</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $vo){?>

					<tr>
						<td><?php echo $vo['id']?></td>
						<td><a href="<?php echo url('addons/edit',array('id'=>$vo['id']))?>"><?php echo $vo['name']?></a></td>
						<td><?php echo $vo['description']?></td>
						<td><?php echo $vo['type_text']?></td>
						<td>
							<a title="编辑" href="<?php echo url('addons/edit',array('id'=>$vo['id']))?>">编辑</a>
							<a class="confirm ajax-get" title="删除" href="<?php echo url('addons/delete',array('id'=>$vo['id']))?>">删除</a>
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