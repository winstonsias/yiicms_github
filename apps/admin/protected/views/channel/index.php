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
**********DATE:2014-12-2**********
*/
?>
<div class="main-title">
		<h2>导航管理</h2>
	</div>

	<div class="cf">
		<a class="btn" href="<?php echo url('channel/add',array('pid'=>$pid))?>">新 增</a>
		<a class="btn ajax-post confirm" url="<?php echo url('channel/delete')?>" target-form="ids">删 除</a>
		<button class="btn list_sort" url="<?php echo url('channel/sort',array('pid'=>intval(get('id'))))?>">排序</button>
	</div>

	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th class="row-selected">
						<input class="checkbox check-all" type="checkbox">
					</th>
					<th>ID</th>
					<th>导航名称</th>
					<th>导航地址</th>
                    <th>排序</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($list as $channel){?>
					<tr>
						<td><input class="ids row-selected" type="checkbox" name="id[]" id="" value="<?php echo $channel['id']?>"> </td>
						<td><?php echo $channel['id']?></td>
						<td><a href="<?php echo url('channel/index',array('pid'=>$channel['id']))?>"><?php echo $channel['title']?></a></td>
						<td><?php echo $channel['url']?></td>
                        <td><?php echo $channel['sort']?></td>
						<td>
							<a title="编辑" href="<?php echo url('channel/edit',array('id'=>$channel['id']))?>">编辑</a>
							<a href="<?php echo url('channel/setStatus',array('ids'=>$channel['id'],'status'=>abs(1-$channel['status'])))?>" class="ajax-get"><?php echo show_status_op($channel['status'])?></a>
							<a class="confirm ajax-get" title="删除" href="<?php echo url('channel/delete',array('id'=>$channel['id']))?>">删除</a>
						</td>
					</tr>

				<?php }?>
			</tbody>
		</table>
	</div>

<script type="text/javascript">
    $(function() {
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
<script type="text/javascript" charset="utf-8">
	//导航高亮
	highlight_subnav('<?php echo url('Channel/index')?>');
</script>