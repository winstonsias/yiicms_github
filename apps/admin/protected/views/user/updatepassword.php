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
**********updatepassword.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-31**********
*/
?>


	<!-- 标题栏 -->

		<div class="tab-wrap">
		<ul class="tab-nav nav">
		<li ><a href="<?php echo url('user/updateNickname')?>">修改昵称</a></li>
		<li class="current"><a href="<?php echo url('user/updatepassword')?>">修改密码</a></li>
		</ul>
		<div class="tab-content">
	<!-- 修改密码表单 -->
	<form action="" method="post" class="form-horizontal">
		<div class="form-item">
			<label class="item-label">原密码：</label>
			<div class="controls">
				<input type="password" name="oldpassword" class="text input-large" autocomplete="off" />
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">新密码：</label>
			<div class="controls">
				<input type="password" name="password" class="text input-large" autocomplete="off" />
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">确认密码：</label>
			<div class="controls">
				<input type="password" name="repassword" class="text input-large" autocomplete="off" />
			</div>
		</div>
		<div class="form-item">
			<button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 认</button>
			<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
		</div>
	</form>
			</div>
	</div>



	<script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/thinkbox/jquery.thinkbox.js"></script>
