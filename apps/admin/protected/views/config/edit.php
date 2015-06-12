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
**********DATE:2014-12-1**********
*/
?>
<div class="main-title">
		<h2><?php isset($info['id'])?'编辑':'新增'?>配置</h2>
	</div>
	<form action="" method="post" class="form-horizontal">
		<div class="form-item">
			<label class="item-label">配置标识<span class="check-tips">（用于C函数调用，只能使用英文且不能重复）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="name" value="<?php echo isset($info['name'])?$info['name']:''?>">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">配置标题<span class="check-tips">（用于后台显示的配置标题）</span></label>
			<div class="controls">
				<input type="text" class="text input-large" name="title" value="<?php echo isset($info['title'])?$info['title']:''?>">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">排序<span class="check-tips">（用于分组显示的顺序）</span></label>
			<div class="controls">
				<input type="text" class="text input-small" name="sort" value="<?php echo isset($info['sort'])?$info['sort']:0?>">
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">配置类型<span class="check-tips">（系统会根据不同类型解析配置值）</span></label>
			<div class="controls">
				<select name="type">
				<?php foreach (C('CONFIG_TYPE_LIST') as $key=>$type){?>
						<option value="<?php echo $key?>"><?php echo $type?></option>

					<?php }?>
				</select>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">配置分组<span class="check-tips">（配置分组 用于批量设置 不分组则不会显示在系统设置中）</span></label>
			<div class="controls">
				<select name="group">
					<option value="0">不分组</option>
					<?php foreach (C('CONFIG_GROUP_LIST') as $key=>$type){?>
						<option value="<?php echo $key?>"><?php echo $type?></option>

					<?php }?>
					
				</select>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">配置值<span class="check-tips">（配置值）</span></label>
			<div class="controls">
				<label class="textarea input-large">
					<textarea name="value"><?php echo isset($info['value'])?$info['value']:'' ?></textarea>
				</label>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">配置项<span class="check-tips">（如果是枚举型 需要配置该项）</span></label>
			<div class="controls">
				<label class="textarea input-large">
					<textarea name="extra"><?php echo isset($info['extra'])?$info['extra']:'' ?></textarea>
				</label>
			</div>
		</div>
		<div class="form-item">
			<label class="item-label">说明<span class="check-tips">（配置详细说明）</span></label>
			<div class="controls">
				<label class="textarea input-large">
					<textarea name="remark"><?php echo isset($info['remark'])?$info['remark']:'' ?></textarea>
				</label>
			</div>
		</div>
		<div class="form-item">
			<input type="hidden" name="id" value="<?php echo isset($info['id'])?$info['id']:''?>">
			<button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
			<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
		</div>
	</form>

	<script type="text/javascript">
		Think.setValue("type", <?php echo isset($info['type'])?$info['type']:0?>);
		Think.setValue("group", <?php echo isset($info['group'])?$info['group']:0?>);
		//导航高亮
		highlight_subnav('<?php echo url('Config/index')?>');
	</script>