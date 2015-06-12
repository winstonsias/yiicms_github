<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $this->pageTitle;?>|管理平台</title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/module.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/<?php echo $this->config['COLOR_STYLE']?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "<?php echo app()->homeUrl;?>", //当前网站地址
            "APP"    : "<?php echo app()->homeUrl;?>", //当前项目地址
            "PUBLIC" : "<?php echo C('static_url')?>", //项目公共目录地址
            "DEEP"   : "{:C('URL_PATHINFO_DEPR')}", //PATHINFO分割符
            "MODEL"  : ["{:C('URL_MODEL')}", "{:C('URL_CASE_INSENSITIVE')}", "{:C('URL_HTML_SUFFIX')}"],
            "VAR"    : ["{:C('VAR_MODULE')}", "{:C('VAR_CONTROLLER')}", "{:C('VAR_ACTION')}"]
        }
    })();
    </script>
    <script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/think.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/js/common.js"></script>
    <script type="text/javascript">
 		
        $(function(){
           
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        });
    </script>
    <block name="style"></block>
</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav"> 
            <?php ?>
            <?php foreach ($this->adminmenu['main'] as $v){?>
                
                <li class="<?php echo isset($v['class'])?$v['class']:''?>"><a href="<?php echo url($v['url'])?>"><?php echo $v['title']?></a></li>
            <?php }?>
            
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php $user_auth=getSession('user_auth'); echo $user_auth['username']?>"><?php echo  $user_auth['username']?></em></li>
                <li><a href="<?php echo url('user/updatePassword')?>">修改密码</a></li>
                <li><a href="<?php echo url('user/updateNickname')?>">修改昵称</a></li>
                <li><a href="<?php echo url('public/logout')?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        <block name="sidebar">
            <div id="subnav" class="subnav">
                <?php $_extra_menu=''; if($_extra_menu) echo extra_menu($_extra_menu,$this->adminmenu)?>
               
                
                
            <?php 
            if(strtolower(app()->controller->id)!='article')//内容单独左侧
            {
            foreach ($this->adminmenu['child'] as $key=>$val){
                   
                ?>
                <h3><i class="icon icon-unfold" ></i><?php echo $key?></h3>
                <ul class="side-sub-menu">
  				    <?php foreach ($val as $v){?>
                  <li>
                        <a class="item" href="<?php echo isset($v['url'])?url($v['url']):'';?>"><?php echo isset($v['title'])?$v['title']:'';?></a>
                    </li>
                          <?php }?>  
                        </ul>
                
            <?php 
            }
            }else{
                 
                include_once Yii::app()->getViewPath().DS.'article'.DS.'sidemenu.php';
            }
            ?>

                    <!-- /子导航 -->

            </div>
        </block>
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            <block name="nav">
            <!-- nav -->
           
            <!-- nav -->
            </block>

            <?php echo $content;?>
        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">copyright-winston</div>
                <div class="fr">V1.0</div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    
    <block name="script"></block>
</body>
</html>

