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
**********article_list.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-16**********
*/
?>
<?php foreach ($lists as $article)
{
?>

 <div class="">
                    <h3><a href="<?php echo url('article/detial',array('id'=>$article['id']))?>"><?php echo $article['title']?></a></h3>
                </div>
                <div>
                    <p class="lead"><?php echo $article['description']?></p>
                </div>
                <div>
                    <span><a href="<?php echo url('article/detial',array('id'=>$article['id']))?>">查看全文</a></span>
                    <span class="pull-right">
                        <span class="author"></span>
                        <span>于 <?php echo date('Y-m-d H:i',$article['create_time'])?></span> 发表在 <span>
                        <a href="<?php echo url('article/lists',array('category'=>get_category_name($article['category_id'])))?>">
                        <?php echo get_category_title($article['category_id'])?></a></span> ( 阅读：<?php echo $article['view']?> )
                    </span>
                </div>
                <hr/>
<?php }?>


<div class="onethink pagination pagination-right pagination-large">
                <div>  
                
                <div class="page">
	          <?php 
 //分页widget代码: 
 $this->widget('CLinkPager',array(   //此处Yii内置的是CLinkPager，我继承了CLinkPager并重写了相关方法
    'header'=>'',
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'pages' => $pages,       
    'maxButtonCount'=>10,    //分页数目
    'htmlOptions'=>array(
       'class'=>'paging',   //包含分页链接的div的class
     )
  ));
 ?>
	    </div>
                </div>            </div>
 