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
**********step1.php**********
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
                            <li class="active"><a href="javascript:;">环境检测</a></li>
                            <li><a href="javascript:;">创建数据库</a></li>
                            <li><a href="javascript:;">安装</a></li>
                            <li><a href="javascript:;">完成</a></li>
                    	</ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="jumbotron masthead">
            <div class="container">
                
    <h1>环境检测</h1>
    <table class="table">
        <caption><h2>运行环境检查</h2></caption>
        <thead>
            <tr>
                <th>项目</th>
                <th>所需配置</th>
                <th>当前配置</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($env as $item){?>
          
                <tr>
                    <td><?php echo $item[0]?></td>
                    <td><?php echo $item[1]?></td>
                    <td><i class="ico-<?php echo $item[4]?>">&nbsp;</i><?php echo $item[3]?></td>       
                </tr>
         
            <?php }?>
        </tbody>
    </table>
    <?php if(isset($dirfile)){?>
	
    <table class="table">
        <caption><h2>目录、文件权限检查</h2></caption>
        <thead>
            <tr>
                <th>目录/文件</th>
                <th>所需状态</th>
                <th>当前状态</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($dirfile as $item){?>
            
                <tr>
                    <td><?php echo $item[3]?></td>
                    <td><i class="ico-success">&nbsp;</i>可写</td>
                    <td><i class="ico-<?php echo $item[2]?>">&nbsp;</i><?php echo $item[1]?></td>   
                </tr>
            
            <?php }?>
        </tbody>
    </table>
	
	<?php }?>
    <table class="table">
        <caption><h2>函数依赖性检查</h2></caption>
        <thead>
            <tr>
                <th>函数名称</th>
                <th>检查结果</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($func as $item){?>
            <volist name="func" id="item">
                <tr>
                    <td><?php echo $item[0]?>()</td>
                    <td><i class="ico-<?php echo $item[2]?>">&nbsp;</i><?php echo $item[1]?></td>
                </tr>
            </volist>
            <?php }?>
        </tbody>
    </table>
            </div>
        </div>


        <!-- Footer
        ================================================== -->
        <footer class="footer navbar-fixed-bottom">
            <div class="container">
                <div>
                	  <a class="btn btn-success btn-large" href="<?php echo url('Index/index')?>">上一步</a>
    <a class="btn btn-primary btn-large" href="<?php echo url('install/step2')?>">下一步</a>
                </div>
            </div>
        </footer>