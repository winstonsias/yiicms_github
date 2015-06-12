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
**********user.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-4**********
*/
?>
<block name="body">
<div class="tab-wrap">
    <ul class="tab-nav nav">
        <li><a href="<?php echo url('AuthManager/access',array('group_name'=>gp('group_name'),'group_id'=>gp('group_id')))?>">访问授权</a></li>
        <li><a href="<?php echo url('AuthManager/category',array('group_name'=>gp('group_name'),'group_id'=>gp('group_id')))?>">分类授权</a></li>
		<li class="current"><a href="javascript:;">成员授权</a></li>
	    <li class="fr">
		    <select name="group">
		    <?php foreach ($auth_group as $vo){?>
			    
				    <option value="<?php echo url('AuthManager/user',array('group_id'=>$vo['id'],'group_name'=>$vo['title']))?>" <?php echo $vo['id']==$this_group['id']?"selected":""?> ><?php echo $vo['title']?></option>
			    
			    <?php }?>
		    </select>
	    </li>
    </ul>
    <!-- 数据列表 -->
    <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="">UID</th>
		<th class="">昵称</th>
		<th class="">最后登录时间</th>
		<th class="">最后登录IP</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
    <?php foreach ($lists as $vo){?>
		
		<tr>
			<td> <?php echo $vo['uid']?></td>
			<td><?php echo $vo['nickname']?></td>
			<td><span> <?php echo date("Y-m-d H:i:s",$vo['last_login_time'])?></span></td>
			<td><span> <?php echo $vo['last_login_ip']?></span></td>
			<td> <?php echo $vo['status_text']?></td>
			<td><a href="<?php echo url('AuthManager/removeFromGroup',array('uid'=>$vo['uid'],'group_id'=>gp('group_id')))?>" class="ajax-get">解除授权</a>

                </td>
		</tr>
		
		<?php }?>
	</tbody>
    </table>


    </div>
	<div class="main-title">
		<div class="page_nav fl">
			       <?php 
 //分页widget代码: 
 $this->widget('CLinkPager',array(   
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
		<div id="add-to-group" class="tools fr">
			<form class="add-user" action="<?php echo url('AuthManager/addToGroup')?>" method="post" enctype="application/x-www-form-urlencoded" >
				<input class="text input-4x" type="text" name="uid" placeholder="请输入uid,多个用英文逗号分隔">
				<input type="hidden" name="group_id" value="<?php echo gp('group_id')?>">
                <button type="submit" class="btn ajax-post" target-form="add-user">新 增</button>
			</form>
		</div>
	</div>

</div>
</block>

<block name="script">
<script type="text/javascript" charset="utf-8">
	$('select[name=group]').change(function(){
		location.href = this.value;
	});
    //导航高亮
    highlight_subnav("<?php echo url('AuthManager/index')?>");
</script>
</block>