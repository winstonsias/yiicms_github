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
**********step2.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-12**********
*/
?>
  <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" target="_blank" href="">WinYiiCMS</a>
                    <div class="nav-collapse collapse">
                    	<ul id="step" class="nav">
                    		 <li ><a href="javascript:;">安装协议</a></li>
                            <li><a href="javascript:;">环境检测</a></li>
                            <li class="active"><a href="javascript:;">创建数据库</a></li>
                            <li><a href="javascript:;">安装</a></li>
                            <li><a href="javascript:;">完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>


<div class="jumbotron masthead">
            <div class="container">
                

    <h1>创建数据库</h1>
    <form action="" method="post" target="_self">
        <div class="create-database">
            <h2>数据库连接信息</h2>
            <div>
                <select name="db[]">
                    <option>mysqli</option>
	                <option selected>mysql</option>
                </select>
                <span>数据库连接类型，服务器支持的情况下建议使用mysqli</span>
            </div>
            <div>
                <input type="text" name="db[]" value="127.0.0.1">
                <span>数据库服务器，数据库服务器IP，一般为127.0.0.1</span>
            </div>
            <div>
                <input type="text" name="db[]" value="">
                <span>数据库名</span>
            </div>
            <div>
                <input type="text" name="db[]" value="">
                <span>数据库用户名</span>
            </div>
            <div>
                <input type="password" name="db[]" value="">
                <span>数据库密码</span>
            </div>

            <div>
                <input type="text" name="db[]" value="3306">
                <span>数据库端口，数据库服务连接端口，一般为3306</span>
            </div>

            <div>
                <input type="text" name="db[]" value="yiicms_">
                <span>数据表前缀，同一个数据库运行多个系统时请修改为不同的前缀</span>
            </div>
        </div>

        <div class="create-database">
            <h2>创始人帐号信息</h2>
            <div>
                <input type="text" name="admin[]" value="Administrator">
                <span>用户名</span>
            </div>
            <div>
                <input type="password" name="admin[]" value="">
                <span>密码</span>
            </div>
            <div>
                <input type="password" name="admin[]" value="">
                <span>确认密码</span>
            </div>
            <div>
                <input type="text" name="admin[]" value="">
                <span>邮箱，请填写正确的邮箱便于收取提醒邮件</span>
            </div>
        </div>
    </form>
            </div>
        </div>


  <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	  <a class="btn btn-success btn-large" href="<?php echo url('install/step1')?>">上一步</a>
    <button id="submit" type="button" class="btn btn-primary btn-large" onclick="$('form').submit();return false;">下一步</button>
                </div>
            </div>
        </footer>