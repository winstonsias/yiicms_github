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
**********operate.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-12**********
*/
?>
<block name="body">
	<div class="main-title">
		<h2><?php echo $operate?>分类</h2>
	</div>
	<div class="tab-wrap">
		<div class="tab-content">
			<form action="<?php echo url('category/'.$type)?>" method="post" class="form-horizontal">
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item">
						<label class="item-label">目标分类<span class="check-tips">（将<?php echo $operate?>至的分类）</span></label>
						<div class="controls">
							<select name="to">
							<?php foreach ($list as $vo){?>
									<option value="<?php echo $vo['id']?>"><?php echo $vo['title']?></option>
								<?php }?>
							</select>
						</div>
					</div>
				</div>

				<div class="form-item">
					<input type="hidden" name="from" value="<?php echo $from?>">
					<button type="submit" id="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
					<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
				</div>
			</form>
		</div>
	</div>
</block>
<script>highlight_subnav('<?php echo url('Category/index')?>');</script>