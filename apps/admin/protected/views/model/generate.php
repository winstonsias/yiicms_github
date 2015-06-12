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
**********generate.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-12-1**********
*/
?>
	<div class="main-title cf">
		<h2>生成模型</h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" action="" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">数据表<span class="check-tips">（当前数据库的所有表）</span></label>
						<div class="controls">
							<select name="table">
							<?php foreach ($tables as $tb){?>

									<option value="<?php echo $tb?>"><?php echo $tb?></option>

								<?php }?>
							</select>
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">

						<button class="btn submit-btn ajax-post no-refresh" type="submit" target-form="form-horizontal">生 成</button>
						<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</block>
<block name="script">
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/jquery.dragsort-0.5.1.min.js"></script>
<script type="text/javascript" charset="utf-8">
    //导航高亮
    highlight_subnav('<?php echo url('Model/index')?>');

    $(function(){
    	showTab();
    })
</script>