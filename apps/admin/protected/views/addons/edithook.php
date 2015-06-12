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
**********edithook.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2015-1-7**********
*/
?>

<div class="main-title cf">
		<h2><?php echo  is_null($data)?"新增":"编辑"?>钩子</h2>
	</div>

	<!-- 修改密码表单 -->
	<form action="<?php echo url('Addons/updateHook')?>" method="post" class="form-horizontal">
		<div class="form-item cf">
			<label class="item-label">钩子名称<span class="check-tips">（需要在程序中先添加钩子，否则无效）</span></label>
			<div class="controls">
				<input type="text" value="<?php echo !is_null($data)?$data['name']:''?>" name="name" class="text input-large">
			</div>
		</div>
		<div class="form-item cf">
			<label class="item-label">钩子描述<span class="check-tips">（钩子的描述信息）</span></label>
			<div class="controls">
				<label class="textarea input-large">
				<textarea name="description" ><?php echo !is_null($data)?$data['description']:''?></textarea></label>
			</div>
		</div>
		<div class="form-item cf">
			<label class="item-label">钩子类型<span class="check-tips">（区分钩子的主要用途）</span></label>
			<div class="controls">
				<select name="type">
				<?php foreach (C('HOOKS_TYPE') as $key=>$vo){?>
						<option value="<?php echo $key?>" <?php echo $data['type']==$key?'selected':''?> ><?php echo $vo?></option>

					<?php }?>
				</select>
			</div>
		</div>
		<?php if($data){?>
			<div class="form-item cf">
				<label class="item-label">钩子挂载的插件排序<span class="check-tips">（拖动后保存顺序，影响同一个钩子挂载的插件执行先后顺序）</span></label>
				<div class="controls">
					<input type="hidden" name="addons" value="<?php echo $data['addons']?>" readonly>
					<?php if(empty($data['addons'])){?>
		
						暂无插件，无法排序
					
					<?php }else{?>
					<ul id="sortUl" class="dragsort">
					<?php foreach (explode(',',$data['addons']) as $addons_vo){?>
					
							<li class="getSort"><b>&times;</b><em><?php echo $addons_vo?></em></li>
					
						<?php }?>
					</ul>
					
					<script type="text/javascript">
						$(function(){
							$("#sortUl").dragsort({
	                            dragSelector:'li',
	                            placeHolderTemplate: '<li class="draging-place">&nbsp;</li>',
	                            dragEnd:function(){
	                            	updateVal();
	                            }
	                        });

							$('#sortUl li b').click(function(){
                            	$(this).parent().remove();
                            	updateVal();
                            });

							// 更新排序后的隐藏域的值
	                        function updateVal() {
	                        	var sortVal = [];
                            	$('#sortUl li').each(function(){
                            		sortVal.push($('em',this).text());
                            	});
                                $("input[name='addons']").val(sortVal.join(','));
	                        }
						})
					</script>
					<?php }?>
				</div>
			</div>

		<?php }?>
		<input type="hidden" name="id" value="<?php echo !is_null($data)?$data['id']:''?>">
		<button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
		<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
	</form>

		<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/jquery.dragsort-0.5.1.min.js"></script>
	<script type="text/javascript">
		$(function(){
			//导航高亮
			highlight_subnav('<?php echo url("Addons/hooks")?>');
		})
	</script>