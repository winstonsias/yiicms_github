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
**********updatenickname.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-15**********
*/
?>
	<div class="tab-wrap">
		<ul class="tab-nav nav">
		<li class="current"><a href="<?php echo url('user/updateNickname')?>">修改昵称</a></li>
		<li ><a href="<?php echo url('user/updatepassword')?>">修改密码</a></li>
		</ul>
		<div class="tab-content">
	<!-- 修改密码表单 -->
    <form action="" method="post" class="form-horizontal" autocomplete="off">
		<div class="form-item">
			<label class="item-label">密码：<span class="check-tips">（请输入密码）</span></label>
			<div class="controls">
				<input type="password" name="password" class="text input-large"  autocomplete="off"/>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">昵称：<span class="check-tips">（请输入新昵称）</span></label>
			<div class="controls">
				<input type="text" name="nickname" class="text input-large" autocomplete="off" value="<?php echo get_nickname();?>"/>
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

