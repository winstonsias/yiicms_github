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
**********step3.php**********
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
    <li ><a href="javascript:;">环境检测</a></li>
    <li ><a href="javascript:;">创建数据库</a></li>
    <li class="active"><a href="javascript:;">安装</a></li>
    <li><a href="javascript:;">完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>

<div class="jumbotron masthead">
            <div class="container">
                
    <h1>安装</h1>
    <div id="show-list" class="install-database">
    </div>
    <script type="text/javascript">
        var list   = document.getElementById('show-list');
        function showmsg(msg, classname){
            var li = document.createElement('p'); 
            li.innerHTML = msg;
            classname && li.setAttribute('class', classname);
            list.appendChild(li);
            document.scrollTop += 30;
        }
    </script>
            </div>
        </div>

 <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	  <button class="btn btn-warning btn-large disabled">正在安装，请稍后...</button>
                </div>
            </div>
        </footer>
