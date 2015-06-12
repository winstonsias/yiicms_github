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
**********Menu.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class Menu extends LjhModel
{
    public function tableName()
    {
        return "{{menu}}";
    }
    public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hide, is_dev', 'numerical', 'integerOnly'=>true),
			array('title, group', 'length', 'max'=>50),
			array('pid, sort', 'length', 'max'=>10),
			array('url, tip', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, pid, sort, url, hide, tip, group, is_dev', 'safe', 'on'=>'search'),
			array('title,url','required'),
		);
	}
    
    //获取树的根到子节点的路径
	public function getPath($id){
		$path = array();
		$criteria=new CDbCriteria();
		$criteria->select='id,pid,title';
		$criteria->condition='id=:id';
		$criteria->params=array(':id'=>$id);
		$nav = $this->find($criteria);
		$path[] = $nav;
		if($nav['pid'] >1){
			$path = array_merge($this->getPath($nav['pid']),$path);
		}
		return $path;
	}
	
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}