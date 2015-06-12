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
**********InstallController.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-9**********
*/
class InstallController extends LjhController
{
    public function init()
    {
        parent::init();
    	if(Yii::app()->getSession()->get('step') === null){
			$this->redirect(array('Index/index'));
		}

        if(is_file(MODULE_PATH . 'Data/install.lock')){
			exit('已经成功安装，请不要重复安装!');
		}

    }
	//安装第一步，检测运行所需的环境设置
	public function actionStep1(){
		setSession('error', false);

		//环境检测
		$env = check_env();

		//目录文件读写检测
		
		$dirfile = check_dirfile();

		
        setSession('step', 1);
		//函数检测
		$func = check_func();


		$this->render('step1',array('dirfile'=>$dirfile,'env'=>$env,'func'=>$func));
	}
	
	
	//安装第二步，创建数据库
	public function actionStep2(){
	    $db=post('db');
	    $admin=post('admin');
		if(app()->request->isPostRequest){
			//检测管理员信息
			if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
				$this->error('请填写完整管理员信息');
				exit;
			} else if($admin[1] != $admin[2]){
				$this->error('确认密码和密码不一致');
				exit;
			} else {
				$info = array();
				list($info['username'], $info['password'], $info['repassword'], $info['email'])
				= $admin;
				//缓存管理员信息
				setSession('admin_info', $info);
			}

			//检测数据库配置
			if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
				$this->error('请填写完整的数据库配置');
				exit;
			} else {
				$DB = array();
				list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
					 $DB['DB_PORT'], $DB['DB_PREFIX']) = $db;
				//缓存数据库配置
				setSession('db_config', $DB);

				//创建数据库
				$dbname = $DB['DB_NAME'];
				//unset($DB['DB_NAME']);
				$sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
				switch ($DB['DB_TYPE'])
				{
				    case 'mysqli':
				        $conn=new mysqli($DB['DB_HOST'],$DB['DB_USER'],$DB['DB_PWD'],'',$DB['DB_PORT']);
				        if (mysqli_connect_errno()){ 
				            $this->error('数据库链接出错！'. mysqli_connect_error());
				             exit;
				        } 
				       $stmt = $conn->prepare($sql);
				       $stmt->execute();
				       $conn->close();
				       break;
				    case 'mysql':
				        $conn=mysql_connect("{$DB['DB_HOST']}:{$DB['DB_PORT']}",$DB['DB_USER'],$DB['DB_PWD']);
				        if(!$conn)
				        {
				            $this->error('数据库链接出错！');
				             exit;
				        }
				        mysql_query($sql);
				        mysql_close($conn);
				        break;
				        default:
				            $this->error('请选择数据库链接类型！');
				             exit;
				}
				
			}

			//跳转到数据库安装页面
			$this->redirect(array('install/step3'));
		} else {

			getSession('error') && $this->error('环境检测没有通过，请调整环境后重试！');

			$step = getSession('step');

			if($step != 1 && $step != 2){
				$this->redirect(array('install/step1'));
			}

			setSession('step', 2);
			$this->render('step2');
		}
	}
	
	
//安装第三步，安装数据表，创建配置文件
	public function actionStep3(){
		if(getSession('step') != 2){
			$this->redirect(array('install/step2'));
		}

		$this->render('step3');

		//连接数据库
		$dbconfig = getSession('db_config');
		
		//创建数据表
		create_tables($dbconfig, $dbconfig['DB_PREFIX']);

		//注册创始人帐号
		$auth  = build_auth_key();
		$admin = getSession('admin_info');
		register_administrator($dbconfig, $dbconfig['DB_PREFIX'], $admin, $auth);

		//创建配置文件
		$conf 	=	write_config($dbconfig, $auth);
		setSession('config_file',$conf);
		if(getSession('error')){
			show_msg(getSession('error'));
		} else {
			setSession('step', 3);
			$this->forward('Index/complete');
		}
	}
	
}