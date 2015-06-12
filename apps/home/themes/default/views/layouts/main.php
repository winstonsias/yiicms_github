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
**********main.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-9**********
*/
?>

<meta charset="UTF-8">
<title><?php echo C('WEB_SITE_TITLE')?></title>
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/docs.css" rel="stylesheet">
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/onethink.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/js/html5shiv.js"></script>
<![endif]-->
<block name="style"></block>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/js/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/js/bootstrap.min.js"></script>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="{:U('index/index')}">WinYiiCMS</a>
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="nav-collapse collapse">
               <ul class="nav">
               <?php $this->widget('NavWidget'); ?>  
                                </ul>

            </div>
            <div class="nav-collapse collapse pull-right">
                
                    <ul class="nav" style="margin-right:0">
                        <li>
                            <a href="{:U('User/login')}">登录</a>
                        </li>
                        <li>
                            <a href="{:U('User/register')}" style="padding-left:0;padding-right:0">注册</a>
                        </li>
                    </ul>
                
            </div>
        </div>
    </div>
</div>
<?php echo $content?>

<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>

    <footer class="footer">
      <div class="container">
          <p> 本站由 <strong><a href="#" target="_blank">WinYiiCMS</a></strong> 强力驱动</p>
      </div>
    </footer>

