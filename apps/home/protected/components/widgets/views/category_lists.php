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
**********category_lists.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-16**********
*/
?>
<?php foreach ($category as $cate){?>
<?php if($current==$cate['id'])
{?>
	
		<li class="active">
			<a href="<?php echo url('article/lists',array('category'=>$cate['name']))?>">
				<i class="icon-chevron-right"></i><?php echo $cate['title']?>
			</a>
		</li>

	<?php }else{?>
		<li>
			<a href="<?php echo url('article/lists',array('category'=>$cate['name']))?>">
				<i class="icon-chevron-right"></i><?php echo $cate['title']?>
			</a>
		</li>

	<?php }?>
<?php }?>