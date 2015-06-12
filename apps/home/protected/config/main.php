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
define('INSTALL_APP_PATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR);
define('CMS_FOLDER_NAME', 'yiicms');
define('CMS_FOLDER', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR );
Yii::setPathOfAlias(CMS_FOLDER_NAME, dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR);
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..', 
    'theme'=>'default',
	'defaultController' => 'Index/index', 
	'params' => array(
		'main_params' => require (dirname(__FILE__) . '/main_config.php')
	), 
	'import' => array(
		'application.models.*', 
		'application.components.*', 
		'application.components.widgets.*', //加载widget
		CMS_FOLDER_NAME . '.components.*'
	), 
	
	'components' => array(
		'urlManager' => array(
			'urlFormat' => 'path', 
			'showScriptName' => true
		), 
		'assetManager'=>array( 
                    // 设置存放assets的目录
                    'basePath'=>CMS_FOLDER.'#runtime/home/assets',
                
                ), 
		'cache' => array(
			'class' => 'system.caching.CFileCache', 
			'cachePath' => CMS_FOLDER . "#runtime/home/cache"
		), 
		
		'log' => array(
			'class' => 'CLogRouter', 
			'routes' => array(
						/*array(
							'class' => CMS_FOLDER_NAME . '.extensions.yii-debug-toolbar.YiiDebugToolbarRoute'
						), */
						array(
					'class' => 'CFileLogRoute', 
					'levels' => 'error', 
					'logPath' => CMS_FOLDER . "#runtime/home"
				)
			)
		),
		'db'=>require(dirname(__FILE__) . '/../../../common/dbconfig.php'), 
	)
);