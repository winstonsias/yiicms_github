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
**********function.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-12**********
*/
/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env(){
	$items = array(
		'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
		'php'     => array('PHP版本', '5.3', '5.3+', PHP_VERSION, 'success'),
		//'mysql'   => array('MYSQL版本', '5.0', '5.0+', '未知', 'success'), //PHP5.5不支持mysql版本检测
		'upload'  => array('附件上传', '不限制', '2M+', '未知', 'success'),
		'gd'      => array('GD库', '2.0', '2.0+', '未知', 'success'),
		'disk'    => array('磁盘空间', '5M', '不限制', '未知', 'success'),
	);

	//PHP环境检测
	if($items['php'][3] < $items['php'][1]){
		$items['php'][4] = 'error';
		setSession('error', true);
	}

	//数据库检测
	// if(function_exists('mysql_get_server_info')){
	// 	$items['mysql'][3] = mysql_get_server_info();
	// 	if($items['mysql'][3] < $items['mysql'][1]){
	// 		$items['mysql'][4] = 'error';
	// 		session('error', true);
	// 	}
	// }

	//附件上传检测
	if(@ini_get('file_uploads'))
		$items['upload'][3] = ini_get('upload_max_filesize');

	//GD库检测
	$tmp = function_exists('gd_info') ? gd_info() : array();
	if(empty($tmp['GD Version'])){
		$items['gd'][3] = '未安装';
		$items['gd'][4] = 'error';
		setSession('error', true);
	} else {
		$items['gd'][3] = $tmp['GD Version'];
	}
	unset($tmp);

	//磁盘空间检测
	if(function_exists('disk_free_space')) {
		$items['disk'][3] = floor(disk_free_space(INSTALL_APP_PATH) / (1024*1024)).'M';
	}

	return $items;
}
/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile(){
	$items = array(
		array('dir',  '可写', 'success', './Uploads/Download'),
		array('dir',  '可写', 'success', './Uploads/Picture'),
		array('dir',  '可写', 'success', './Uploads/Editor'),
		array('dir',  '可写', 'success', './#runtime'),
		array('dir',  '可写', 'success', './apps/install/protected/Data'),
		//array('dir', '可写', 'success', './Application/User/Conf'),
        array('file', '可写', 'success', './apps/common/dbconfig.php'),
        array('file', '可写', 'success', './apps/common/common_config.php'),
        array('file', '可写', 'success', './apps/admin/protected/extensions/user/config/config.php'),
	);

	foreach ($items as &$val) {
		if('dir' == $val[0]){
			if(!is_writable(INSTALL_APP_PATH . $val[3])) {
				if(is_dir(INSTALL_APP_PATH . $val[3])) {
					$val[1] = '可读';
					$val[2] = 'error';
					setSession('error', true);
				} else {
					$val[1] = '不存在';
					$val[2] = 'error';
					setSession('error', true);
				}
			}
		} else {
			if(file_exists(INSTALL_APP_PATH . $val[3])) {
				if(!is_writable(INSTALL_APP_PATH . $val[3])) {
					$val[1] = '不可写';
					$val[2] = 'error';
					setSession('error', true);
				}
			} else {
				if(!is_writable(dirname(INSTALL_APP_PATH . $val[3]))) {
					$val[1] = '不存在';
					$val[2] = 'error';
					setSession('error', true);
				}
			}
		}
	}

	return $items;
}
/**
 * 函数检测
 * @return array 检测数据
 */
function check_func(){
	$items = array(
		array('mysql_connect',     '支持', 'success'),
		array('file_get_contents', '支持', 'success'),
		array('mb_strlen',		   '支持', 'success'),
	);

	foreach ($items as &$val) {
		if(!function_exists($val[0])){
			$val[1] = '不支持';
			$val[2] = 'error';
			$val[3] = '开启';
			setSession('error', true);
		}
	}
    if(!extension_loaded('pdo_mysql'))
    {
        $items[]=array(
            'pdo_mysql','不支持','error'
        );
        setSession('error', true);
    }else {
        $items[]=array(
            'pdo_mysql','支持','success'
        );
    }
	return $items;
}



/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($dbconfig, $prefix = ''){
    $conn=mysql_connect("{$dbconfig['DB_HOST']}:{$dbconfig['DB_PORT']}",$dbconfig['DB_USER'],$dbconfig['DB_PWD']);
    mysql_query('set names utf8');
	//读取SQL文件
	$sql = file_get_contents(MODULE_PATH . 'Data/install.sql');
	$sql = str_replace("\r", "\n", $sql);
	$sql = explode(";\n", $sql);
    mysql_select_db($dbconfig['DB_NAME']);
	//替换表前缀
	$orginal = Yii::app()->params['main_params']['ORIGINAL_TABLE_PREFIX'];
	$sql = str_replace(" `{$orginal}", " `{$prefix}", $sql);
//var_dump($sql);exit;
	//开始安装
	show_msg('开始安装数据库...');
	foreach ($sql as $value) {
		$value = trim($value);
		if(empty($value)) continue;
		if(substr($value, 0, 12) == 'CREATE TABLE') {
			$name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
			$msg  = "创建数据表{$name}";
			if(false !== mysql_query($value,$conn)){
				show_msg($msg . '...成功');
			} else {
				show_msg($msg . '...失败！', 'error');
				setSession('error', true);
			}
		} else {
		   
			mysql_query($value,$conn);
		}

	}
	mysql_close($conn);
}

function register_administrator($dbconfig, $prefix, $admin, $auth){
     $conn=mysql_connect("{$dbconfig['DB_HOST']}:{$dbconfig['DB_PORT']}",$dbconfig['DB_USER'],$dbconfig['DB_PWD']);
    mysql_query('set names utf8');
     mysql_select_db($dbconfig['DB_NAME']);
	show_msg('开始注册创始人帐号...');
	$sql = "INSERT INTO `[PREFIX]ucenter_member` VALUES " .
		   "('1', '[NAME]', '[PASS]', '[EMAIL]', '', '[TIME]', '[IP]', 0, 0, '[TIME]', '1')";

	$password = user_md5($admin['password'], $auth);
	$sql = str_replace(
		array('[PREFIX]', '[NAME]', '[PASS]', '[EMAIL]', '[TIME]', '[IP]'),
		array($prefix, $admin['username'], $password, $admin['email'], time(), app()->request->userHostAddress),
		$sql);
	//执行sql
	mysql_query($sql,$conn);


	$sql = "INSERT INTO `[PREFIX]member` VALUES ".
		   "('1', '[NAME]', '0', '0', '', '0', '1', '0', '[TIME]', '0', '[TIME]', '1');";
	$sql = str_replace(
		array('[PREFIX]', '[NAME]', '[TIME]'),
		array($prefix, $admin['username'], time()),
		$sql);
	mysql_query($sql,$conn);
	show_msg('创始人帐号注册完成！');
	mysql_close($conn);
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = ''){
	echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
	flush();
	ob_flush();
}

/**
 * 生成系统AUTH_KEY
 */
function build_auth_key(){
	$chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$chars .= '`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
	$chars  = str_shuffle($chars);
	return substr($chars, 0, 40);
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function user_md5($str, $key = ''){
	return '' === $str ? '' : md5(sha1($str) . $key);
}


/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config, $auth){
	if(is_array($config)){
		//读取配置内容
		$conf = file_get_contents(MODULE_PATH . 'Data/conf.tpl');
		$user = file_get_contents(MODULE_PATH . 'Data/user.tpl');
		$common_config = file_get_contents(MODULE_PATH . 'Data/common_config.tpl');
		//替换配置项
		foreach ($config as $name => $value) {
			$conf = str_replace("[{$name}]", $value, $conf);
			$common_config = str_replace("[{$name}]", $value, $common_config);
			$user = str_replace("[{$name}]", $value, $user);
		}

		$conf = str_replace('[AUTH_KEY]', $auth, $conf);
        $user = str_replace('[AUTH_KEY]', $auth, $user);
		//写入应用配置文件
		
			if(file_put_contents(APP_PATH . 'common/dbconfig.php', $conf)&&
			   file_put_contents(APP_PATH . 'common/common_config.php', $common_config)&&
			   file_put_contents(APP_PATH . 'admin/protected/extensions/user/config/config.php', $user)){
				show_msg('配置文件写入成功');
			} else {
				show_msg('配置文件写入失败！', 'error');
				setSession('error', true);
			}
			return '';
		

	}
}
