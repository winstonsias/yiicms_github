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
**********index.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-9**********
*/
error_reporting(0);
$yii=dirname(__FILE__).'/../../../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
$globals =dirname(__FILE__). '/../../globals.php';
define('MODULE_PATH', dirname(__FILE__).'/protected/');
define('APP_PATH', dirname(__FILE__).'/../');
require_once ($globals);
require_once dirname(__FILE__).'/protected/common/function.php';//加载模块公共函数
// remove the following line when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();
