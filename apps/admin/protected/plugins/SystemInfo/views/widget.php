<div class="span<?php echo $config['width']?>">
	<div class="columns-mod">
		<div class="hd cf">
			<h5><?php echo $config['title']?></h5>
			<div class="title-opt">
			</div>
		</div>
		<div class="bd">
			<div class="sys-info">
				<table>
					
					<tr>
						<th>服务器操作系统</th>
						<td><?php echo PHP_OS?></td>
					</tr>
					<tr>
						<th>YIICMS版本</th>
						<td><?php echo get_yiicms_version();?></td>
					</tr>
					<tr>
						<th>运行环境</th>
						<td><?php echo $_SERVER['SERVER_SOFTWARE']?></td>
					</tr>
					<tr>
						<th>MYSQL版本</th>
						<?php 
						    $conn=app()->db;
						    $sql="select version() as v;";
						    $command = $conn->createCommand($sql);
						    $system_info_mysql=$command->queryRow();   
						?>

						<td><?php echo $system_info_mysql['v'];?></td>
					</tr>
					<tr>
						<th>上传限制</th>
						<td><?php echo ini_get('upload_max_filesize')?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>



<div class="span<?php echo $config['width']?>">
	<div class="columns-mod">
		<div class="hd cf">
			<h5>产品团队</h5>
			<div class="title-opt">
			</div>
		</div>
		<div class="bd">
			<div class="sys-info">
				<table>
					<tr>
						<th>总策划</th>
						<td>winston</td>
					</tr>
					<tr>
						<th>产品设计及研发团队</th>
						<td>winston</td>
					</tr>
					<tr>
						<th>界面及用户体验团队</th>
						<td>winston</td>
					</tr>
					<tr>
						<th>官方网址</th>
						<td></td>
					</tr>
					<tr>
						<th>官方QQ群</th>
						<td></td>
					</tr>
					<tr>
						<th>BUG反馈</th>
						<td></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>