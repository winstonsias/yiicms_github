
<?php 
    switch ($addons_config['editor_type'])
    {
        case 1:
            echo '<input type="hidden" name="parse" value="0">
		<script type="text/javascript">
			$(\'textarea[name="'.$addons_data['name'].'"]\').height(\''.$addons_config['editor_height'].'\');
		</script>';   
            break;
        case 2:
            echo '<input type="hidden" name="parse" value="0">';
            fuwenben($addons_config,$addons_data);
            break;
        case 3:
            echo '<script type="text/javascript" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/jquery-migrate-1.2.1.min.js"></script>
		<script charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/xheditor/xheditor-1.2.1.min.js"></script>
		<script charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/xheditor/xheditor_lang/zh-cn.js"></script>
		<script type="text/javascript" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/xheditor/xheditor_plugins/ubb.js"></script>
		<script type="text/javascript">
		var submitForm = function (){
			$(\'textarea[name="'.$addons_data['name'].'"]\').closest(\'form\').submit();
		}
		$(\'textarea[name="'.$addons_data['name'].'"]\').attr(\'id\', \'editor_id_'.$addons_data['name'].'\')
		$(\'#editor_id_'.$addons_data['name'].'\').xheditor({
			tools:\'full\',
			showBlocktag:false,
			forcePtag:false,
			beforeSetSource:ubb2html,
			beforeGetSource:html2ubb,
			shortcuts:{\'ctrl+enter\':submitForm},
			"height":"'.$addons_config['editor_height'].'",
			"width" :"100%"
		});
		</script>
		<input type="hidden" name="parse" value="1">';
		break;
        case 4:
            echo '<link rel="stylesheet" href="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/thinkeditor/skin/default/style.css">
		<script type="text/javascript" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/thinkeditor/jquery.thinkeditor.js"></script>
		<script type="text/javascript">
			$(function(){
				$(\'textarea[name="'.$addons_data['name'].'"]\').attr(\'id\', \'editor_id_'.$addons_data['name'].'\');
				var options = {
					"items"  : "h1,h2,h3,h4,h5,h6,-,link,image,-,bold,italic,code,-,ul,ol,blockquote,hr,-,fullscreen",
			        "width"  : "100%", //宽度
			        "height" : "'.$addons_config['editor_height'].'", //高度
			        "lang"   : "zh-cn", //语言
			        "tab"    : "    ", //Tab键插入的字符， 默认为四个空格
					"uploader": "'.addons_url("Editor://Upload/upload").'"
			    };
			    $("#editor_id_'.$addons_data['name'].'").thinkeditor(options);
			})
		</script>
		<input type="hidden" name="parse" value="2">';
    }
    
    //富文本
    function fuwenben($addons_config,$addons_data)
    {
        if($addons_config['editor_wysiwyg']==1)
        {
            $resizetype=$addons_config['editor_resize_type']==1?1:0;
            echo '<link rel="stylesheet" href="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/kindeditor/default/default.css" />
			<script charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/kindeditor/kindeditor-min.js"></script>
			<script charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/kindeditor/zh_CN.js"></script>
			<script type="text/javascript">
				var editor;
				KindEditor.ready(function(K) {
					editor = K.create(\'textarea[name="'.$addons_data['name'].'"]\', {
						allowFileManager : false,
						themesPath: K.basePath,
						width: "100%",
						height: "'.$addons_config['editor_height'].'",
						resizeType: '.$resizetype.',
						pasteType : 2,
						urlType : "absolute",
						fileManagerJson : "'.url('upload/fileManagerJson').'",
						uploadJson : "'.addons_url("EditorForAdmin://Upload/ke_upimg").'"
					});
				});

				$(function(){
					//传统表单提交同步
					$(\'textarea[name="'.$addons_data['name'].'"]\').closest(\'form\').submit(function(){
						editor.sync();
					});
					//ajax提交之前同步
					$(\'button[type="submit"],#submit,.ajax-post\').click(function(){
						editor.sync();
					});
				})
			</script>';
        }else {
            $resizetype=$addons_config['editor_resize_type']==1?true:fasle;
            echo '<script type="text/javascript" charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/ueditor/ueditor.config.js"></script>
			<script type="text/javascript" charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/ueditor/ueditor.all.js"></script>
			<script type="text/javascript" charset="utf-8" src="'.Yii::app()->params['main_params']['static_url'].'/extendstatic/ueditor/lang/zh-cn/zh-cn.js"></script>
			<script type="text/javascript">
				$(\'textarea[name="'.$addons_data['name'].'"]\').attr(\'id\', \'editor_id_'.$addons_data['name'].'\');
				window.UEDITOR_HOME_URL = "'.Yii::app()->params['main_params']['static_url'].'/extendstatic/ueditor";
				window.UEDITOR_CONFIG.initialFrameHeight = parseInt(\''.$addons_config['editor_height'].'\');
				window.UEDITOR_CONFIG.scaleEnabled = '.$resizetype.';
				window.UEDITOR_CONFIG.imageUrl = \'{:addons_url("EditorForAdmin://Upload/ue_upimg")}\';
				window.UEDITOR_CONFIG.imagePath = \'\';
				window.UEDITOR_CONFIG.imageFieldName = \'imgFile\';
				UE.getEditor(\'editor_id_{'.$addons_data['name'].'\');
			</script>';
        }
        
    }
?>
