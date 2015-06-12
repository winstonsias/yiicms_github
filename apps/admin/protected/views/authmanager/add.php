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
**********add.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-3**********
*/
?>
<!-- 管理员用户组新增和编辑页面 -->
<block name="body">
	<div class="main-title">
		<h2><?php echo isset($auth_group)?'编辑':'新增'?>用户组</h2>
	</div>

    <form action="<?php echo url('AuthManager/writeGroup')?>" enctype="application/x-www-form-urlencoded" method="POST"
            class="form-horizontal">
        <div class="form-item">
            <label for="auth-title" class="item-label">用户组</label>
            <div class="controls">
                <input id="auth-title" type="text" name="title" class="text input-large" value="<?php echo isset($auth_group)?$auth_group['title']:''?>"/>
            </div>
        </div>
        <div class="form-item">
            <label for="auth-description" class="item-label">描述</label>
            <div class="controls">
                <label class="textarea input-large">
                <textarea id="auth-description" type="text" name="description"><?php echo isset($auth_group)?$auth_group['description']:''?></textarea></label>
            </div>
        </div>
        <div class="form-item">
            <input type="hidden" name="id" value="<?php echo isset($auth_group)?$auth_group['id']:''?>" />
            <button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </form>
<block>
<block name="script">
<script type="text/javascript" charset="utf-8">
    //导航高亮
    highlight_subnav("<?php echo url('AuthManager/index')?>");
</script>
</block>
