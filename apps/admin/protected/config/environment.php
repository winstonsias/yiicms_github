<?php
define('CMS_FOLDER_NAME', 'yiicms');
//cms文件夹名称
define('CORE_FOLDER', dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . DIRECTORY_SEPARATOR . 'framework');
define('CMS_FOLDER', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR );
define('CMS_WIDGETS', CMS_FOLDER . DIRECTORY_SEPARATOR . 'widgets');
define('COMMON_FOLDER', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'common');
//StripSlashes all GET, POST, COOKIE
if (get_magic_quotes_gpc())
{
	function stripslashes_gpc(&$value)
	{
		$value = stripslashes($value);
	}
	array_walk_recursive($_GET, 'stripslashes_gpc');
	array_walk_recursive($_POST, 'stripslashes_gpc');
	array_walk_recursive($_COOKIE, 'stripslashes_gpc');
}
/**
 * This class helps you to config your Yii application
 * environment.
 * Any comments please post a message in the forum
 * Enjoy it!
 *
 * @name Environment
 * @author Fernando Torres | Marciano Studio
 * @version 1.0
 */
class Environment
{
	const DEVELOPMENT = 100;
	const TEST = 200;
	const STAGE = 300;
	const PRODUCTION = 400;
	private $_mode = 0;
	private $_debug;
	private $_trace_level;
	private $_config;
	/**
	 * Main configuration
	 * This is the general configuration that uses all environments
	 */
	private function _main()
	{
		$config= array(
			'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..', 
		    'runtimePath'=>CMS_FOLDER.'#runtime/admin',
			'id' => 'backend', 
			'name' => 'winston CMS', 
			'sourceLanguage' => 'zh_cn', 
			'language' => 'zh_cn', 
			'defaultController' => 'public', 
			'preload' => array(
				'log'
			), 
			'import' => array(
				'application.models.*', 
				'application.components.*', 
				CMS_FOLDER_NAME . '.components.*', 
			), 
			'modules' => array(
				'error' => array(
					'class' => CMS_FOLDER_NAME . '.modules.error.ErrorModule'
				), 
				
			), 
			'defaultController'=>'public/login',
			
			'components' => array(
				'cache' => array(
					'class' => 'system.caching.CFileCache',
			        'cachePath'=>CMS_FOLDER."#runtime/admin/cache",
				), 
				
                'log' => array(
					'class' => 'CLogRouter', 
					'routes' => array(
						/*array(
							'class' => CMS_FOLDER_NAME . '.extensions.yii-debug-toolbar.YiiDebugToolbarRoute'
						), */
						array(
							'class' => 'CFileLogRoute', 
							'levels' => 'error, warning,trace, info',
							'logPath'=>CMS_FOLDER."#runtime/admin",
						)
					)
				),
				'errorHandler' => array(
					'errorAction' => 'site/error'
				), 
				'urlManager' => array(
					'urlFormat' => 'path', 
					'showScriptName' => true
				), 
				'session' => array(), 
				'request' => array(
					'class' => CMS_FOLDER_NAME . '.components.HttpRequest', 
					'enableCsrfValidation' => false, 
					'enableCookieValidation' => true, 
					'noCsrfValidationRoutes' => array(
						'install'
					)
				), 
				'messages' => array(
					'cachingDuration' => 86400
				),
				'WPlugin'=>array(
				    'class'=>'application.components.WPlugin',
				    'pluginDir'=>realpath(dirname(__FILE__)) . '/../plugins/',
				),
				'db'=>require(dirname(__FILE__) . '/../../../common/dbconfig.php'), 

				'assetManager'=>array( 
                    // 设置存放assets的目录
                    'basePath'=>CMS_FOLDER.'#runtime/admin/assets',
                
                ), 
				
				
			), 
			'params' => array(
				'environment' => $this->_mode,
			    'main_params' => require(dirname(__FILE__) . '/main_config.php'),  
			),
			
		);
		return $config;
	}
	/**
	 * Development configuration
	 * Usage:
	 * - Local website
	 * - Local DB
	 * - Show all details on each error.
	 * - Gii module enabled
	 */
	private function _development()
	{
		// Set Time Zone
		date_default_timezone_set('PRC');
		define('SITE_PATH', '{{site_path}}');
		define('RESOURCE_URL', '{{resource_url}}');
		define('RESOURCES_FOLDER', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'resources');
		// Define EMAIL INFORMATION
		define('ADMIN_EMAIL', '{{admin_email}}');
		// Define Related to Upload File Size
		define('UPLOAD_MAX_SIZE', 10485760);
		//10mb
		define('UPLOAD_MIN_SIZE', 1);
		// 1 byte
		return array(
			'modules' => array(
				'gii' => array(
					'class' => 'system.gii.GiiModule', 
					'password' => '123456', 
					'ipFilters' => array(
						'127.0.0.1', 
						'::1'
					), 
					'newFileMode' => 438, 
					'newDirMode' => 511, 
					'generatorPaths' => array(
						CMS_FOLDER_NAME . '.gii'
					)
				)
			), 
			
			'components' => array(
				/*'db' => array(
					'connectionString' => 'mysql:host=localhost;dbname=yiicms', 
					'schemaCachingDuration' => 3600, 
					'emulatePrepare' => true, 
					'username' => 'root', 
					'password' => '', 
					'charset' => 'utf8', 
					'tablePrefix' => 'winston_', 
					'enableProfiling' => true, 
					'enableParamLogging' => true
				), */
				
			)
		);
	}
	/**
	 * Test configuration
	 * Usage:
	 * - Local website
	 * - Local DB
	 * - Standard production error pages (404,500, etc.)
	 * @var array
	 */
	private function _test()
	{
		// Set Time Zone
		date_default_timezone_set('PRC');
		define('SITE_PATH', '{{site_path}}');
		define('RESOURCE_URL', '{{resource_url}}');
		define('RESOURCES_FOLDER', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'resources');
		// Define EMAIL INFORMATION
		define('ADMIN_EMAIL', '{{admin_email}}');
		return array(
			'components' => array(
				/*'db' => array(
					'connectionString' => 'mysql:host=localhost;dbname=yiicms', 
					'schemaCachingDuration' => 3600, 
					'emulatePrepare' => true, 
					'username' => 'root', 
					'password' => '', 
					'charset' => 'utf8', 
					'tablePrefix' => 'winston_', 
					'enableProfiling' => true, 
					'enableParamLogging' => true
				), */
				'fixture' => array(
					'class' => 'system.test.CDbFixtureManager'
				), 
				'log' => array(
					'class' => 'CLogRouter', 
					'routes' => array(
						array(
							'class' => 'CFileLogRoute', 
							'levels' => 'error, warning,trace, info'
						), 
						array(
							'class' => 'CWebLogRoute', 
							'levels' => 'error, warning'
						), 
						array(
							'class' => CMS_FOLDER_NAME . '.extensions.pqp.PQPLogRoute', 
							'categories' => 'application.*, exception.*, system.*', 
							'levels' => 'error, warning, info'
						)
					)
				)
			)
		);
	}
	/**
	 * Stage configuration
	 * Usage:
	 * - Online website
	 * - Production DB
	 * - All details on error
	 */
	private function _stage()
	{
		// Set Time Zone
		date_default_timezone_set('PRC');
		define('SITE_PATH', '{{site_path}}');
		define('RESOURCE_URL', '{{resource_url}}');
		define('RESOURCES_FOLDER', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'resources');
		// Define EMAIL INFORMATION
		define('ADMIN_EMAIL', '{{admin_email}}');
		return array(
			'components' => array(
				/*'db' => array(
					'connectionString' => 'mysql:host=localhost;dbname=yiicms', 
					'schemaCachingDuration' => 3600, 
					'emulatePrepare' => true, 
					'username' => 'root', 
					'password' => '', 
					'charset' => 'utf8', 
					'tablePrefix' => 'winston_', 
					'enableProfiling' => true, 
					'enableParamLogging' => true
				), */
				'log' => array(
					'class' => 'CLogRouter', 
					'routes' => array(
						array(
							'class' => 'CFileLogRoute', 
							'levels' => 'error, warning, trace, info'
						)
					)
				)
			)
		);
	}
	/**
	 * Production configuration
	 * Usage:
	 * - online website
	 * - Production DB
	 * - Standard production error pages (404,500, etc.)
	 */
	private function _production()
	{
		// Set Time Zone
		date_default_timezone_set('PRC');
		define('SITE_PATH', '{{site_path}}');
		define('RESOURCE_URL', '{{resource_url}}');
		define('RESOURCES_FOLDER', dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'resources');
		// Define EMAIL INFORMATION
		define('ADMIN_EMAIL', '{{admin_email}}');
		// Define Related to Upload File Size
		define('UPLOAD_MAX_SIZE', 10485760);
		//10mb
		define('UPLOAD_MIN_SIZE', 1);
		// 1 byte
		return array(
			'components' => array(
				/*'db' => array(
					'connectionString' => 'mysql:host=localhost;dbname=yiicms', 
					'schemaCachingDuration' => 3600, 
					'emulatePrepare' => true, 
					'username' => 'root', 
					'password' => '', 
					'charset' => 'utf8', 
					'tablePrefix' => 'winston_', 
					'enableProfiling' => true, 
					'enableParamLogging' => true
				), */
				'log' => array(
					'class' => 'CLogRouter', 
					'routes' => array(
						array(
							'class' => 'CFileLogRoute', 
							'levels' => 'error, warning'
						), 
						array(
							'class' => 'CEmailLogRoute', 
							'levels' => 'error, warning', 
							'emails' => ADMIN_EMAIL
						)
					)
				)
			)
		);
	}
	/**
	 * Returns the debug mode
	 * @return Bool
	 */
	public function getDebug()
	{
		return $this->_debug;
	}
	/**
	 * Returns the trace level for YII_TRACE_LEVEL
	 * @return int
	 */
	public function getTraceLevel()
	{
		return $this->_trace_level;
	}
	/**
	 * Returns the configuration array depending on the mode
	 * you choose
	 * @return array
	 */
	public function getConfig()
	{
		return $this->_config;
	}
	/**
	 * Initilizes the Environment class with the given mode
	 * @param constant $mode
	 */
	public function __construct($mode)
	{
		$this->_mode = $mode;
		$this->setConfig();
	}
	/**
	 * Sets the configuration for the choosen environment
	 * @param constant $mode
	 */
	private function setConfig()
	{
		switch ($this->_mode)
		{
			case self::DEVELOPMENT :
				$this->_config = array_merge_recursive($this->_main(), $this->_development());
				$this->_debug = TRUE;
				$this->_trace_level = 3;
				break;
			case self::TEST :
				$this->_config = array_merge_recursive($this->_main(), $this->_test());
				$this->_debug = FALSE;
				$this->_trace_level = 0;
				break;
			case self::STAGE :
				$this->_config = array_merge_recursive($this->_main(), $this->_stage());
				$this->_debug = TRUE;
				$this->_trace_level = 0;
				break;
			case self::PRODUCTION :
				$this->_config = array_merge_recursive($this->_main(), $this->_production());
				$this->_debug = FALSE;
				$this->_trace_level = 0;
				break;
			default :
				$this->_config = $this->_main();
				$this->_debug = TRUE;
				$this->_trace_level = 0;
				break;
		}
	}
}

