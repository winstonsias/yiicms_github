<?php
require_once (dirname(__FILE__) . '/protected/config/environment.php');
$environment = new Environment(Environment::DEVELOPMENT);
// change the following paths if necessary
$yii = CORE_FOLDER . '/yii.php';
$globals = CMS_FOLDER . '/globals.php';

defined('YII_DEBUG') or define('YII_DEBUG', $environment->getDebug());
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $environment->getTraceLevel());


require_once ($yii);
require_once ($globals);

require_once dirname(__FILE__).'/protected/common/function.php';//加载模块公共函数

require_once dirname(__FILE__).'/protected/config/constant.php';//加载常量

Yii::setPathOfAlias('common', COMMON_FOLDER);
Yii::setPathOfAlias(CMS_FOLDER_NAME, CMS_FOLDER);
Yii::setPathOfAlias('cmswidgets', CMS_WIDGETS);
$app=Yii::createWebApplication($environment->getConfig());
$app->onBeginRequest='set_hook';
$app->run();

