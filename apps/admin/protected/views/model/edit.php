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
**********edit.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-24**********
*/
?>

<block name="body">
	<div class="main-title cf">
		<h2>
		<?php if($this->getAction()->getId()=='add'){
		
		    echo '新增';
		}else{echo '编辑';}?>模型</h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<ul class="tab-nav nav">
			<li data-tab="tab1" class="current"><a href="javascript:void(0);">基 础</a></li>
			<li data-tab="tab2"><a href="javascript:void(0);">设 计</a></li>
			<li data-tab="tab3"><a href="javascript:void(0);">高 级</a></li>
		</ul>
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" action="" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">模型标识<span class="check-tips">（请输入文档模型标识）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="name" value="<?php echo $info['name']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">模型名称<span class="check-tips">（请输入模型的名称）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="title" value="<?php echo $info['title']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">模型类型<span class="check-tips">（目前支持独立模型和文档模型）</span></label>
						<div class="controls">
							<select name="extend">
								<option value="0">独立模型</option>
								<option value="1">文档模型</option>
							</select>
						</div>
					</div>
				</div>

				<div id="tab2" class="tab-pane tab2">
					<div class="form-item cf">
						<label class="item-label">字段管理<span class="check-tips">（只有新增了字段，该表才会真正建立）</span></label>

						<div class="controls">
						<div class="form-item cf edit_sort edit_sort_l form_field_sort">
							<span>字段列表 		[ <a href="<?php echo url('attribute/add',array('model_id'=>$info['id']))?>" target="_balnk">新增</a>
							<a href="<?php echo url('attribute/index',array('model_id'=>$info['id']))?>" target="_balnk">管理</a> ] </span>
							<ul class="dragsort">
							<?php foreach ($fields as $k=>$field){?>
										<li >
											<em ><?php echo $field['title']?> [<?php echo $field['name']?>]</em>
										</li>
								
								<?php }?>
							</ul>
						</div>

						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">表单显示分组<span class="check-tips">（用于表单显示的分组，以及设置该模型表单排序的显示）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="field_group" value="<?php echo $info['field_group']?>">
						</div>
					</div>
					<div class="form-item cf">
					<label class="item-label">表单显示排序<span class="check-tips">（直接拖动进行排序）</span></label>
					<?php foreach (parse_field_attr($info['field_group']) as $key=>$vo){?>
					
						<div class="form-item cf edit_sort edit_sort_l form_field_sort">
							<span><?php echo $vo?></span>
							<ul class="dragsort needdragsort" data-group="<?php echo $key?>">
							<?php $i=0; foreach ($fields as $field){ $i++;?>
								
								<?php if(($field['group']==$key||($i==1&&!isset($field['group'])))&&$field['is_show']==1){?>
									
										<li class="getSort">
											<em data="<?php echo $field['id']?>"><?php echo $field['title']?> [<?php echo $field['name']?>]</em>
											<input type="hidden" name="field_sort[<?php echo $key?>][]" value="<?php echo $field['id']?>"/>
										</li>
								
									<?php }?>
								
								<?php }?>
							</ul>
						</div>
			
					<?php }?>
					</div>

					<div class="form-item cf">
						<label class="item-label">列表定义<span class="check-tips">（默认列表模板的展示规则）</span></label>
						<div class="controls">
							<label class="textarea input-large">
								<textarea name="list_grid"><?php echo $info['list_grid']?></textarea>
							</label>
						</div>
					</div>

					<div class="form-item cf">
						<label class="item-label">默认搜索字段<span class="check-tips">（默认列表模板的默认搜索项）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="search_key" value="<?php echo $info['search_key']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">高级搜索字段<span class="check-tips">（默认列表模板的高级搜索项）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="search_list" value="<?php echo $info['search_list']?>">
						</div>
					</div>
				</div>

				<!-- 高级 -->
				<div id="tab3" class="tab-pane tab3">
					<div class="form-item cf">
						<label class="item-label">列表模板<span class="check-tips">（自定义的列表模板，放在Application\Admin\View\Think下，不写则使用默认模板）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="template_list" value="<?php echo $info['template_list']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">新增模板<span class="check-tips">（自定义的新增模板，放在Application\Admin\View\Think下，不写则使用默认模板）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="template_add" value="<?php echo $info['template_add']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">编辑模板<span class="check-tips">（自定义的编辑模板，放在Application\Admin\View\Think下，不写则使用默认模板）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="template_edit" value="<?php echo $info['template_edit']?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">列表数据大小<span class="check-tips">（默认列表模板的分页属性）</span></label>
						<div class="controls">
							<input type="text" class="text input-small" name="list_row" value="<?php echo $info['list_row']?>">
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">
						<input type="hidden" name="id" value="<?php echo $info['id']?>"/>
						<button class="btn submit-btn ajax-post no-refresh" type="submit" target-form="form-horizontal">确 定</button>
						<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</block>
<block name="script">
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/jquery.dragsort-0.5.1.min.js"></script>
<script type="text/javascript" charset="utf-8">
Think.setValue("extend", <?php echo $info['extend']?$info['extend']:0?>);

//导航高亮
highlight_subnav('<?php echo url('Model/index')?>');

$(function(){
	showTab();
})
//拖曳插件初始化
$(function(){
	$(".needdragsort").dragsort({
	     dragSelector:'li',
	     placeHolderTemplate: '<li class="draging-place">&nbsp;</li>',
	     dragBetween:true,	//允许拖动到任意地方
	     dragEnd:function(){
	    	 var self = $(this);
	    	 self.find('input').attr('name', 'field_sort[' + self.closest('ul').data('group') + '][]');
	     }
	 });
})
</script>
</block>

