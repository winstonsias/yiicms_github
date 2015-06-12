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
**********LjhDocumentModelBase.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-19**********
*/
abstract class LjhDocumentModelBase extends LjhModel
{
	/**
	 * 获取模型详细信息
	 * @param  integer $id 文档ID
	 * @return array       当前模型详细信息
	 */
	public function detail($id){
		$data = $this->findByPk($id);
		if(!$data){
			$this->myerror = '获取详细信息出错！';
			return false;
		}
		return $data;
	}
    /**
	 * 新增或者更新数据
	 */
	abstract public function winston_update($id = 0);

	/**
	 * 保存为草稿
	 */
	abstract public function autoSave($id = 0);
}