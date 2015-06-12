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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Yiicms 安装</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/css/install.css" rel="stylesheet">

        <script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/js/jquery-1.10.2.min.js"></script>
        <script src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/bootstrap/js/bootstrap.js"></script>
    </head>

    <body data-spy="scroll" data-target=".bs-docs-sidebar">
    
    <?php echo $content;?>
        
    </body>
</html>
