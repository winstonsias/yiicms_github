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
**********DATE:2014-11-21**********
*/
?>

<block name="body">
	<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/uploadify/jquery.uploadify.min.js"></script>
	<div class="main-title cf">
		<h2>
			编辑<?php echo $data['title']?>[
			<?php if(is_array($this->assign['rightNav'])){?>
		<?php $i=0; foreach ($this->assign['rightNav'] as $nav){ $i++;?>

		<a href="<?php echo url('article/index',array('cate_id'=>$nav['id']))?>"><?php echo $nav['title']?></a>
			
			<?php if(count($this->assign['rightNav'])>$i){echo '<i class="ca"></i>';}?>

		<?php }?>
		
		<?php }?>
			<?php if(!is_null($article)){?>
			: <a href="<?php echo url('article/index',array('cate_id'=>$data['category_id'],'pid'=>$article['id']))?>"><?php echo $article['title']?></a>
			<?php }?>
			]
		</h2>
	</div>
	<!-- 标签页导航 -->
<div class="tab-wrap">
	<ul class="tab-nav nav">
		<?php foreach (parse_config_attr($model['field_group']) as $key=>$group){?>

			<li data-tab="tab<?php echo $key?>" <?php echo $key==1?'class=current':''?> ><a href="javascript:void(0);"><?php echo $group?></a></li>

		<?php }?>
	</ul>
	<div class="tab-content">
	<!-- 表单 -->
	<form id="form" action="" method="post" class="form-horizontal">
		<!-- 基础文档模型 -->
		<!-- 基础文档模型 -->
		<?php foreach (parse_config_attr($model['field_group']) as $key=>$group){?>

        <div id="tab<?php echo $key?>" class="tab-pane <?php echo $key==1?'in':''?> tab<?php echo $key?>">
        	<?php foreach ($fields[$key] as $field){?>

            <?php if($field['is_show']==1||$field['is_show']==2){?>

                <div class="form-item cf">
                    <label class="item-label"><?php echo $field['title']?><span class="check-tips">
                    <?php if(!empty($field['remark'])){?>（<?php echo $field['remark']?>）<?php }?>
                    </span></label>
                    <div class="controls">
                    <?php get_html_by_field_type($field,$data);?>
                        
                        
                    </div>
                </div>

                <?php }?>

            <?php }?>
        </div>

		<?php }?>

		<div class="form-item cf">
			<button class="btn submit-btn ajax-post hidden" id="submit" type="submit" target-form="form-horizontal">确 定</button>
			<a class="btn btn-return" href="<?php app()->request->urlReferrer?>">返 回</a>
			<?php if(C('OPEN_DRAFTBOX')&&(strtolower($this->getAction()->getId())=='add'||$data['status']==3)){?>
			
			<button class="btn save-btn" url="<?php echo url('article/autoSave')?>" target-form="form-horizontal" id="autoSave">
				存草稿
			</button>
	
			<?php }?>
			<input type="hidden" name="id" value="<?php echo isset($data['id'])?$data['id']:''?>"/>
			<input type="hidden" name="pid" value="<?php echo isset($data['pid'])?$data['pid']:''?>"/>
			<input type="hidden" name="model_id" value="<?php echo isset($data['model_id'])?$data['model_id']:''?>"/>
			<input type="hidden" name="category_id" value="<?php echo isset($data['category_id'])?$data['category_id']:''?>">
		</div>
	</form>
	</div>
</div>
</block>

<block name="script">
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<?php if(C('COLOR_STYLE')=='blue_color'){
    echo '<link href=" '.Yii::app()->params['main_params']['static_url'] .'/extendstatic/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">';
}
?>
<link href="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->params['main_params']['static_url']; ?>/extendstatic/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">

Think.setValue("type", "<?php echo $data['type']?$data['type']:''?>");
Think.setValue("display", "<?php echo $data['display']?$data['display']:0?>");

$('#submit').click(function(){
	$('#form').submit();
});

$(function(){
    $('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    showTab();
<?php if(C('OPEN_DRAFTBOX')&&(strtolower($this->getAction()->getId())=='add'||$data['status']==3)){?>

	//保存草稿
	var interval;
	$('#autoSave').click(function(){
        var target_form = $(this).attr('target-form');
        var target = $(this).attr('url')
        var form = $('.'+target_form);
        var query = form.serialize();
        var that = this;

        $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
        $.post(target,query).success(function(data){
            if (data.status==1) {
                updateAlert(data.info ,'alert-success');
                $('input[name=id]').val(data.data.id);
            }else{
                updateAlert(data.info);
            }
            setTimeout(function(){
                $('#top-alert').find('button').click();
                $(that).removeClass('disabled').prop('disabled',false);
            },1500);
        })

        //重新开始定时器
        clearInterval(interval);
        autoSaveDraft();
        return false;
    });

	//Ctrl+S保存草稿
	$('body').keydown(function(e){
		if(e.ctrlKey && e.which == 83){
			$('#autoSave').click();
			return false;
		}
	});

	//每隔一段时间保存草稿
	function autoSaveDraft(){
		interval = setInterval(function(){
			//只有基础信息填写了，才会触发
			var title = $('input[name=title]').val();
			var name = $('input[name=name]').val();
			var des = $('textarea[name=description]').val();
			if(title != '' || name != '' || des != ''){
				$('#autoSave').click();
			}
		}, 1000*parseInt(<?php echo C('DRAFT_AOTOSAVE_INTERVAL')?>));
	}
	autoSaveDraft();
<?php }?>


});
</script>
</block>