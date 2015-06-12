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
**********managergroup.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-3**********
*/
?>

<block name="body">
	<div class="tab-wrap">
		<ul class="tab-nav nav">
			<li class="current"><a href="javascript:;">访问授权</a></li>
            <li><a href="<?php echo url('AuthManager/category',array('group_name'=>gp('group_name'),'group_id'=>gp('group_id')))?>">分类授权</a></li>
			<li><a href="<?php echo url('AuthManager/user',array('group_name'=>gp('group_name'),'group_id'=>gp('group_id')))?>">成员授权</a></li>
			<li class="fr">
				<select name="group">
				<?php foreach ($auth_group as $vo){?>
					
						<option value="<?php echo url('AuthManager/access',array('group_name'=>$vo['title'],'group_id'=>$vo['id']))?>" <?php echo $vo['id']==$this_group['id']?"selected":""?>><?php echo $vo['title']?>
						</option>
					
					<?php }?>
				</select>
			</li>
		</ul>
		<div class="tab-content">
			<!-- 访问授权 -->
			<div class="tab-pane in">
				<form action="<?php echo url('AuthManager/writeGroup')?>" enctype="application/x-www-form-urlencoded" method="POST" class="form-horizontal auth-form">
				<?php foreach ($node_list as $node){?>
					
						<dl class="checkmod">
							<dt class="hd">
								<?php if(isset( $main_rules[$node['url']])){?>
								<label class="checkbox"><input class="auth_rules rules_all" type="checkbox" name="rules[]" value="<?php echo $main_rules[$node['url']] ?>"><?php echo $node['title']?>管理</label>
								<?php }?>
							</dt>
							<dd class="bd">
								
								<?php if(isset($node['child'])){ foreach ($node['child'] as $child){?>
								
                                    <div class="rule_check">
                                        <div>
                                            <label class="checkbox"  title='<?php echo $child['tip']?>'>
                                           <input class="auth_rules rules_row" type="checkbox" name="rules[]" value="<?php echo $auth_rules[$child['url']] ?>"/><?php echo $child['title']?>
                                            </label>
                                        </div>
                               
                                           <?php if(isset($child['operator'])){?><span class="divsion">&nbsp;</span><?php }?>
                                           <span class="child_row">
                                           <?php if(isset($child['operator'])){foreach ($child['operator'] as $op){ ?>
                                              
                                                   <label class="checkbox" title='<?php echo $op['tip']?>'>
                                                       <input class="auth_rules" type="checkbox" name="rules[]"  value="<?php echo $auth_rules[$op['url']] ?>"/><?php echo $op['title']?>
                                                   </label>
                                               
                                               <?php }}?>
                                           </span>
                                      
				                    </div>
								
								<?php }}?>
								
							</dd>
						</dl>
					
<?php } ?>
			        <input type="hidden" name="id" value="<?php echo $this_group['id']?>" />
                    <button type="submit" class="btn submit-btn ajax-post" target-form="auth-form">确 定</button>
                    <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
			    </form>
			</div>

			<!-- 成员授权 -->
			<div class="tab-pane"></div>

			<!-- 分类 -->
			<div class="tab-pane"></div>
		</div>
	</div>

</block>
<block name="script">
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/qtip/jquery.qtip.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/qtip/jquery.qtip.min.css" media="all">
<script type="text/javascript" charset="utf-8">
    +function($){
        var rules = [<?php echo $this_group['rules']?>];
        $('.auth_rules').each(function(){
            if( $.inArray( parseInt(this.value,10),rules )>-1 ){
                $(this).prop('checked',true);
            }
            if(this.value==''){
                $(this).closest('span').remove();
            }
        });

        //全选节点
        $('.rules_all').on('change',function(){
            $(this).closest('dl').find('dd').find('input').prop('checked',this.checked);
        });
        $('.rules_row').on('change',function(){
            $(this).closest('.rule_check').find('.child_row').find('input').prop('checked',this.checked);
        });

        $('.checkbox').each(function(){
            $(this).qtip({
                content: {
                    text: $(this).attr('title'),
                    title: $(this).text()
                },
                position: {
                    my: 'bottom center',
                    at: 'top center',
                    target: $(this)
                },
                style: {
                    classes: 'qtip-dark',
                    tip: {
                        corner: true,
                        mimic: false,
                        width: 10,
                        height: 10
                    }
                }
            });
        });

        $('select[name=group]').change(function(){
			location.href = this.value;
        });
        //导航高亮
        highlight_subnav("<?php echo url('AuthManager/index')?>");
    }(jQuery);
</script>
</block>
