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
**********DATE:2015-1-5**********
*/
?>
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>插件列表</h2>
	</div>
	<div>
		<a href="<?php echo url('Addons/create')?>" class="btn">快速创建</a>
	</div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th>名称</th>
					<th>标识</th>
					<th >描述</th>
					<th width="43px">状态</th>
					<th>作者</th>
					<th width="43px">版本</th>
					<th width="94px">操作</th>
				</tr>
			</thead>
			<tbody>
				
				<?php 
				    foreach ($_list as $vo)
				    {
				?>
				<tr>
					<td><?php echo $vo['title']?> </td>
					<td><?php echo $vo['name'] ?> </td>
					<td><?php echo $vo['description']?></td>
					<td><?php echo $vo['status_text']?></td>
					<td><a target="_blank" href="<?php echo isset($vo['url'])?$vo['url']:''?>"><?php echo $vo['author']?></a></td>
					<td><?php echo $vo['version']?></td>
					<td>
					<?php if($vo['uninstall']==0){
						
							
							Yii::import("application.plugins.{$vo['name']}.{$vo['name']}");
								$class	= get_addon_class($vo['name']);
								if(!class_exists($class)){
									$has_config = 0;
								}else{
									$addon = new $class();
									$has_config = count($addon->getConfig());
								}
							?>
							<?php if ($has_config){ ?>
								<a href="<?php echo url('Addons/config',array('id'=>$vo['id']))?>">设置</a>
							<?php } ?>
							
						<?php if ($vo['status'] >=0){ ?>
						
						<?php if($vo['status']==0){?>
							
								<a class="ajax-get" href="<?php echo url('Addons/enable',array('ids'=>$vo['id']))?>">启用</a>
							
							<?php }else{?>
								<a class="ajax-get" href="<?php echo url('Addons/disable',array('ids'=>$vo['id']))?>">禁用</a>
							
							<?php }?>
						
							
								<a class="ajax-get" href="<?php echo url('Addons/uninstall',array('id'=>$vo['id']))?>">卸载</a>
							<?php }?>
						
							
						<?php }else {?>
						<a class="ajax-get" href="<?php echo url('Addons/install',array('addon_name'=>$vo['name']))?>">安装</a>
						<?php }?>
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