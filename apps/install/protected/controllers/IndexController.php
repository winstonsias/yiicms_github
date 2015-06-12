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
class IndexController extends LjhController
{
    
    //安装首页
	public function actionIndex(){
		if(is_file(MODULE_PATH . 'Data/install.lock')){
			exit('已经成功安装，请不要重复安装!');
		}
		
		Yii::app()->getSession()->add('step', 0);
		Yii::app()->getSession()->add('error', false);
		$this->render('index');
	}
	
	
//安装完成
	public function actionComplete(){
		$step = getSession('step');

		if(!$step){
			$this->redirect('Index/index');
		} elseif($step != 3) {
			$this->redirect(array("Install/step{$step}"));
		}

		$filename=MODULE_PATH . 'Data/install.lock';
		$dir         =  dirname($filename);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
        file_put_contents($filename,"lock");
		setSession('step', null);
		setSession('error', null);
		$this->render('complete',array('info'=>getSession('config_file')));
	}
}