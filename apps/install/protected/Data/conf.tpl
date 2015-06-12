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
	'connectionString' => 'mysql:host=[DB_HOST];port=[DB_PORT];dbname=[DB_NAME]', 
	'schemaCachingDuration' => 3600, 
	'emulatePrepare' => true, 
	'username' => '[DB_USER]', 
	'password' => '[DB_PWD]', 
	'charset' => 'utf8', 
	'tablePrefix' => '[DB_PREFIX]', 
	'enableProfiling' => true, 
	'enableParamLogging' => true
);