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
**********NavWeight.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-14**********
*/
class NavWidget extends CWidget
{
	public function run()
	{
		$nav = Channel::model()->findAll(array(
			'condition' => 'status=1', 
			'order' => 'sort desc'
		));
		$nav = findall_to_array($nav);
		$nav = list_to_tree($nav, "id", "pid", "_");
		foreach ($nav as $v)
		{
			echo '<li><a href=" ' . $v['url'] . '" target="_self">' . $v['title'] . '</a></li> ';
		}
	}
}