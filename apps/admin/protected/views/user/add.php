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
**********DATE:2014-10-30**********
*/
?>

<block name="body">
    <div class="main-title">
        <h2>新增用户</h2>
    </div>
    <form action="<?php echo url('user/add')?>" method="post" class="form-horizontal">
        <div class="form-item">
            <label class="item-label">用户名<span class="check-tips">（用户名会作为默认的昵称）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="username" value="">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">密码<span class="check-tips">（用户密码不能少于6位）</span></label>
            <div class="controls">
                <input type="password" class="text input-large" name="password" value="">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">确认密码</label>
            <div class="controls">
                <input type="password" class="text input-large" name="repassword" value="">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">邮箱<span class="check-tips">（用户邮箱，用于找回密码等安全操作）</span></label>
            <div class="controls">
                <input type="text" class="text input-large" name="email" value="">
            </div>
        </div>
        <div class="form-item">
            <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
        </div>
    </form>
</block>

<block name="script">
    <script type="text/javascript">
        //导航高亮
        highlight_subnav('<?php echo url('user/index')?>');
    </script>
</block>
