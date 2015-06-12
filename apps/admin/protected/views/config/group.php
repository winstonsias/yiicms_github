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
**********group.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-4**********
*/
?>
<block name="body">
	<div class="main-title">
		<h2>网站设置</h2>
	</div>
		<div class="tab-wrap">
		<ul class="tab-nav nav">
		<?php foreach (C('CONFIG_GROUP_LIST') as $key=>$group){?>
			<li <?php echo $id==$key? 'class="current"':""?> ><a href="<?php echo url('Config/group',array('id'=>$key))?>"><?php echo $group?>配置</a></li>


		<?php } ?>
		</ul>
		<div class="tab-content">
	<form action="<?php echo url('Config/save')?>" method="post" class="form-horizontal">
	<?php foreach ($list as $config){?>
	<volist name="list" id="config">
		<div class="form-item">
			<label class="item-label"><?php echo $config['title']?><span class="check-tips">（<?php echo $config['remark']?>）</span> </label>
			<div class="controls">
			<?php switch ($config['type']){
case 0:
    echo '<input type="text" class="text input-small" name="config['.$config['name'].']" value="'.$config['value'].'">';
    break;
case 1:
    echo '<input type="text" class="text input-large" name="config['.$config['name'].']" value="'.$config['value'].'">';
    break;
case 2:
    echo '<label class="textarea input-large">
				<textarea name="config['.$config['name'].']">'.$config['value'].'</textarea>
			</label>';
    break;
case 3:
    echo '<label class="textarea input-large">
				<textarea name="config['.$config['name'].']">'.$config['value'].'</textarea>
			</label>';
    break;
case 4:
    echo '<select name="config['.$config['name'].']">'.get_config_attr_options($config['extra'],$config['value']).'</select>';
    break;
    
    
    
    
	}?>
			
				
			</div>
		</div>
		</volist>
		<?php }?>
		<div class="form-item">
			<label class="item-label"></label>
			<div class="controls">
				<?php if(!$list){?><button type="submit" disabled class="btn submit-btn disabled" target-form="form-horizontal">确 定</button>
<?php }else{?>
				<button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
				<?php }?>
				
				<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
			</div>
		</div>
	</form>
			</div>
	</div>
</block>

