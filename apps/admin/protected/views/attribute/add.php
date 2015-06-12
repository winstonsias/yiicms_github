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
**********add.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-11-24**********
*/
?>
<block name="body">
	<div class="main-title cf">
		<h2><?php echo  isset($info['id'])?'编辑':'新增'?> [<?php echo get_model_by_id($info['model_id'])?>] 属性 : <a href="<?php echo url('attribute/index',array('model_id'=>$info['model_id']))?>">返回列表</a></h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<ul class="tab-nav nav">
			<li data-tab="tab1" class="current"><a href="javascript:void(0);">基 础</a></li>
			<li data-tab="tab2"><a href="javascript:void(0);">高 级</a></li>
		</ul>
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" action="" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">字段名<span class="check-tips">（请输入字段名 英文字母开头，长度不超过30）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="name" value="<?php echo isset($info['name'])?$info['name']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段标题<span class="check-tips">（请输入字段标题，用于表单显示）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="title" value="<?php echo isset($info['title'])?$info['title']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段类型<span class="check-tips">（用于表单中的展示方式）</span></label>
						<div class="controls">
							<select name="type" id="data-type">
								<option value="">----请选择----</option>
								<?php foreach (get_attribute_type() as $key=>$type){?>
								
								<option value="<?php echo $key?>" rule="<?php echo $type[1]?>"><?php echo $type[0]?></option>

								<?php }?>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段定义<span class="check-tips">（字段属性的sql表示）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="field" value="<?php echo isset($info['field'])?$info['field']:''?>" id="data-field">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">参数<span class="check-tips">（布尔、枚举、多选字段类型的定义数据）</span></label>
						<div class="controls">
							<label class="textarea input-large">
								<textarea name="extra"><?php echo isset($info['extra'])?$info['extra']:''?></textarea>
							</label>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">默认值<span class="check-tips">（字段的默认值）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="value" value="<?php echo isset($info['value'])?$info['value']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">字段备注<span class="check-tips">(用于表单中的提示)</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="remark" value="<?php echo isset($info['remark'])?$info['remark']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">是否显示<span class="check-tips">（是否显示在表单中）</span></label>
						<div class="controls">
							<select name="is_show">
								<option value="1">始终显示</option>
								<option value="2">新增显示</option>
								<option value="3">编辑显示</option>
								<option value="0">不显示</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">是否必填<span class="check-tips">（用于自动验证）</span></label>
						<div class="controls">
							<select name="is_must">
								<option value="0">否</option>
								<option value="1">是</option>
							</select>
						</div>
					</div>
                    </div>
                <div id="tab2" class="tab-pane tab2">
					<div class="form-item cf">
						<label class="item-label">验证方式<span class="check-tips"></span></label>
						<div class="controls">
							<select name="validate_type">
								<option value="regex">正则验证</option>
								<option value="function">函数验证</option>
								<option value="unique">唯一验证</option>
								<option value="length">长度验证</option>
                                <option value="in">验证在范围内</option>
                                <option value="notin">验证不在范围内</option>
                                <option value="between">区间验证</option>
                                <option value="notbetween">不在区间验证</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">验证规则<span class="check-tips">（根据验证方式定义相关验证规则）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="validate_rule" value="<?php echo isset($info['validate_rule'])?$info['validate_rule']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">出错提示<span class="check-tips"></span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="error_info" value="<?php echo isset($info['error_info'])?$info['error_info']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">验证时间<span class="check-tips"></span></label>
						<div class="controls">
							<select name="validate_time">
                                <option value="3">始 终</option>
								<option value="1">新 增</option>
								<option value="2">编 辑</option>
								</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成方式<span class="check-tips"></span></label>
						<div class="controls">
							<select name="auto_type">
								<option value="function">函数</option>
								<option value="field">字段</option>
								<option value="string">字符串</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成规则<span class="check-tips">（根据完成方式订阅相关规则）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="auto_rule" value="<?php echo isset($info['auto_rule'])?$info['auto_rule']:''?>">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">自动完成时间<span class="check-tips"></span></label>
						<div class="controls">
							<select name="auto_time">
								<option value="3">始 终</option>
								<option value="1">新 增</option>
								<option value="2">编 辑</option>
							</select>
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">
						<input type="hidden" name="id" value="<?php echo isset($info['id'])?$info['id']:''?>"/>
						<input type="hidden" name="model_id" value="<?php echo isset($info['model_id'])?$info['model_id']:''?>"/>
						<button class="btn submit-btn ajax-post no-refresh" type="submit" target-form="form-horizontal">确 定</button>
						<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</block>
<block name="script">
<script type="text/javascript" charset="utf-8">
//导航高亮
highlight_subnav('<?php echo url('Model/index')?>');
Think.setValue('type', "<?php echo isset($info['type'])?$info['type']:''?>");
Think.setValue('is_show', "<?php echo isset($info['is_show'])?$info['is_show']:'1'?>");
Think.setValue('is_must', "<?php echo isset($info['is_must'])?$info['is_must']:'0'?>");
Think.setValue('validate_time', "<?php echo isset($info['validate_time'])?$info['validate_time']:'3'?>");
Think.setValue('auto_time', "<?php echo isset($info['auto_time'])?$info['auto_time']:'3'?>");
Think.setValue('validate_type', "<?php echo isset($info['validate_type'])?$info['validate_type']:'regex'?>");
Think.setValue('auto_type', "<?php echo isset($info['auto_type'])?$info['auto_type']:'function'?>");
$(function(){
	showTab();
})
<?php if(strtolower($this->getAction()->getId())=='add'){?>

$(function(){
	$('#data-type').change(function(){
		$('#data-field').val($(this).find('option:selected').attr('rule'));
	});
})
<?php }?>
</script>
</block>

