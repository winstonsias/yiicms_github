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
**********DynamicModel.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-4**********
*/
class DynamicModel extends CActiveRecord
{
	
	private static $tableName;
	
	public function __construct($table_name = '')
	{
		if ($table_name === null)
		{
			parent::__construct(null);
		}
		else
		{
			self::$tableName = $table_name;
			parent::__construct();
		}
	}
	
	public static function model($table_name = '')
	{
		self::$tableName = $table_name;
		return parent::model(__CLASS__);
	}
	
	public function tableName()
	{
		return self::$tableName;
	}
}