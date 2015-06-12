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
**********dbconnfig.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-12**********
*/
return array(
	'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=yiicms', 
	'schemaCachingDuration' => 3600, 
	'emulatePrepare' => true, 
	'username' => 'root', 
	'password' => '', 
	'charset' => 'utf8', 
	'tablePrefix' => 'winston_', 
	'enableProfiling' => true, 
	'enableParamLogging' => true
);