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
**********tree.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-11**********
*/
?>
<?php foreach ($tree as $list){?>

	<dl class="cate-item">
		<dt class="cf">
			<form action="<?php echo url('category/edit')?>" method="post">
				<div class="btn-toolbar opt-btn cf">
					<a title="编辑" href="<?php echo url('category/edit',array('id'=>$list['id'],'pid'=>$list['pid']))?>">编辑</a>
					<a title="{$list.status|show_status_op}" href="<?php echo url('category/setStatus',array('ids'=>$list['id'],'status'=>abs(1-$list['status'])))?>" class="ajax-get"><?php echo show_status_op($list['status'])?></a>
					<a title="删除" href="<?php echo url('category/delete',array('id'=>$list['id']))?>" class="confirm ajax-get">删除</a>
					<a title="移动" href="<?php echo url('category/operate',array('type'=>'move','from'=>$list['id']))?>">移动</a>
					<a title="合并" href="<?php echo url('category/operate',array('type'=>'merge','from'=>$list['id']))?>">合并</a>
				</div>
				<div class="fold"><i></i></div>
				<div class="order"><input type="text" name="sort" class="text input-mini" value="<?php echo $list['sort']?>"></div>
				<div class="order"><?php echo $list['allow_publish']?'是':'否'?></div>
				<div class="name">
					<span class="tab-sign"></span>
					<input type="hidden" name="id" value="<?php echo $list['id']?>">
					<input type="text" name="title" class="text" value="<?php echo $list['title']?>">
					<a class="add-sub-cate" title="添加子分类" href="<?php echo url('category/add',array('pid'=>$list['id']))?>">
						<i class="icon-add"></i>
					</a>
					<span class="help-inline msg"></span>
				</div>
			</form>
		</dt>
		<?php if(isset($list['_'])){?>
		
			<dd>
				<?php $this->tree($list['_'])?>
			</dd>

		<?php }?>
	</dl>

<?php }?>