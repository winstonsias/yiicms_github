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
**********config.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-8**********
*/
?>
	<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/uploadify/jquery.uploadify.min.js"></script>
	<div class="main-title cf">
		<h2>插件配置 [<?php echo $data['title']?>  ]</h2>
	</div>
	<form action="<?php echo url("Addons/saveConfig")?>" class="form-horizontal" method="post">
	<?php if(empty($custom_config)){?>
		<?php foreach ($data['config'] as $o_key=>$form){?>
		
				<div class="form-item cf">
					<label class="item-label">
						
						<?php echo $form['title'] ?>
						<?php if(isset($form['tip'])){?>
						
							<span class="check-tips"><?php echo $form['tip']?></span>
						
						<?php }?>
					</label>
					
					<?php set_addons_config($form, $o_key);
				        
				        
				        ?>
						

					</div>
	    <?php }?>
		<?php }else{?>
		
		<?php echo $custom_config?>
			
		<?php }?>
		<input type="hidden" name="id" value="<?php echo gp('id')?>" readonly>
		<button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
		<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
	</form>


<script type="text/javascript" charset="utf-8">
	//导航高亮
	highlight_subnav('<?php echo url("Addons/index")?>');
	if($('ul.tab-nav').length){
		//当有tab时，返回按钮不显示
		$('.btn-return').hide();
	}
	$(function(){
		//支持tab
		showTab();
	})
</script>