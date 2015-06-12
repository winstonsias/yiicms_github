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
**********function.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login(){
    $user = getSession('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return getSession('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}
/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}
/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */
function data_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 */
function check_verify($code, $id = 1){
    Yii::import('ext.verify.Verify');
    $verify = new Verify();
    return $verify->check($code, $id);
}


function C($key)
{
    $db_config=array();
    $main_params=array();
    $db_config=app()->cache->get('DB_CONFIG_DATA');
    $main_params=app()->params['main_params'];
    $config=CMap::mergeArray($db_config, $main_params);
    return $config[$key];
}
// 不区分大小写的in_array实现
function in_array_case($value,$array){
    return in_array(strtolower($value),array_map('strtolower',$array));
}

if(!function_exists('array_column')){
    function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
        if (null === $indexKey) {
            if (null === $columnKey) {
                $result = array_values($input);
            } else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        } else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            } else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
        return $result;
    }
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 动态扩展左侧菜单,main.php里用到
 */
function extra_menu($extra_menu,&$base_menu){
    foreach ($extra_menu as $key=>$group){
        if( isset($base_menu['child'][$key]) ){
            $base_menu['child'][$key] = array_merge( $base_menu['child'][$key], $group);
        }else{
            $base_menu['child'][$key] = $group;
        }
    }
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    Yii::app()->WPlugin->trigger($hook,$params); 
}

/**
 * model findall 对象转数组
 * Enter description here ...
 * @param unknown_type $obj
 */
function findall_to_array($obj)
{
    return CJSON::decode(CJSON::encode($obj),TRUE);
}

function findall_field_to_array($obj,$field='',$retfieldarr=true)
{
    $arr=findall_to_array($obj);
    $_field         =   explode(',', $field);
    $cols           =   array();
    $count          =   count($_field);
    if($retfieldarr)
    {
        foreach ($arr as $result){
            $name   =  $result[$_field[0]];
            if(2==$count) {
                $cols[$name]   =  $result[$_field[1]];
            }else{
                $cols[$name]   =  $result;
            }
        }
    }else {
        foreach ($arr as $result)
        {
            $cols[]=$result[$_field[0]];
        }
    }
    return $cols;
}
/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))) {
    if($data === false || $data === null ){
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row){
        foreach ($map as $col=>$pair){
            if(isset($row[$col]) && isset($pair[$row[$col]])){
                $data[$key][$col.'_text'] = $pair[$row[$col]];
            }
        }
    }
    
    return $data;
}


 // 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}
/**
 * 返回配置option
 * Enter description here ...
 * @param unknown_type $extra  配置项解析后的数组
 * @param unknown_type $selectedid 选中id
 */
function get_config_attr_options($extra,$selectedid)
{
    $arr=parse_config_attr($extra);
    $retstr="";
    foreach ($arr as $key=>$vo)
    {
        $selected="";
        if($key==$selectedid) $selected="selected";
        $retstr.= '<option value="'.$key.'" '.$selected.'>'.$vo.'</option>';
    }
    return  $retstr;
}

// 获取数据的状态操作
function show_status_op($status) {
    switch ($status){
        case 0  : return    '启用';     break;
        case 1  : return    '禁用';     break;
        case 2  : return    '审核';		break;
        default : return    false;      break;
    }
}
/**
 * 获取文档模型信息
 * @param  integer $id    模型ID
 * @param  string  $field 模型字段
 * @return array
 */
function get_document_model($id = null, $field = null){
    static $list;

    /* 非法分类ID */
    if(!(is_numeric($id) || is_null($id))){
        return '';
    }

    /* 读取缓存数据 */
    if(empty($list)){
        $list = app()->cache->get('DOCUMENT_MODEL_LIST');
    }

    /* 获取模型名称 */
    if(empty($list)){
        $model = DocModel::model()->findAll(
            array(
                'condition'=>'status=1 and extend=1',
            )
        );
        $model=findall_to_array($model);
        foreach ($model as $value) {
            $list[$value['id']] = $value;
        }
        app()->cache->set('DOCUMENT_MODEL_LIST', $list); //更新缓存
    }
    /* 根据条件返回数据 */
    if(is_null($id)){
        return $list;
    } elseif(is_null($field)){
        return $list[$id];
    } else {
        return $list[$id][$field];
    }
}

//获取除了index.php之外的uri   上传图片显示图片路径
function getUriTrimIndex()
{
    $uri=app()->homeUrl;
    $uri=str_replace('index.php', '', $uri);
    $uri=$uri.'../../';
    return $uri;
}
/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 */
function get_cover($cover_id, $field = null){
    if(empty($cover_id)){
        return false;
    }
    $picture = Picture::model()->findByPk($cover_id,'status=1');
    $picture=findall_to_array($picture);
    return empty($field) ? $picture['path'] : $picture[$field];
}

//$_POST二维数组转为一维数组
function post_to_one_array($data)
{
    if(is_array($data))
    {
        foreach ($data as $k=>$v)
        {
            if(is_array($data[$k]))
            {
                $data[$k]=implode(',', $v);
                
            }
        }
    }
    return $data;
}

/**
 * 获取参数的所有父级分类
 * @param int $cid 分类id
 * @return array 参数分类和父类的信息集合
 */
function get_parent_category($cid){
    if(empty($cid)){
        return false;
    }
    $cates  =   Category::model()->findAll(
        array(
            'condition'=>'status=1',
            'select'=>'id,title,pid',
            'order'=>'sort asc'
        )
    );
    $cates=findall_to_array($cates);
    $child  =   get_category($cid);	//获取参数分类的信息
    $pid    =   $child['pid'];
    $temp   =   array();
    $res[]  =   $child;
    while(true){
        foreach ($cates as $key=>$cate){
            if($cate['id'] == $pid){
                $pid = $cate['pid'];
                array_unshift($res, $cate);	//将父分类插入到数组第一个元素前
            }
        }
        if($pid == 0){
            break;
        }
    }
    return $res;
}
/**
 * 获取分类信息并缓存分类
 * @param  integer $id    分类ID
 * @param  string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null){
    static $list;

    /* 非法分类ID */
    if(empty($id) || !is_numeric($id)){
        return '';
    }

    /* 读取缓存数据 */
    if(empty($list)){
        //$list = app()->cache->get('sys_category_list');
    }

    /* 获取分类名称 */
    if(!isset($list[$id])){
        $cate = Category::model()->findByPk($id);
        if(!$cate || 1 != $cate['status']){ //不存在分类，或分类被禁用
            return '';
        }
        $list[$id] = $cate;
        app()->cache->set('sys_category_list', $list); //更新缓存
    }
    return is_null($field) ? $list[$id] : $list[$id][$field];
}

/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 */
function get_status_title($status = null){
    if(!isset($status)){
        return false;
    }
    switch ($status){
        case -1 : return    '已删除';   break;
        case 0  : return    '禁用';     break;
        case 1  : return    '正常';     break;
        case 2  : return    '待审核';   break;
        default : return    false;      break;
    }
}

// 获取子文档数目
function get_subdocument_count($id=0){
    return  Document::model()->count('pid='.$id);
}

/**
 * 获取文档的类型文字
 * @param string $type
 * @return string 状态文字 ，false 未获取到
 */
function get_document_type($type = null){
    if(!isset($type)){
        return false;
    }
    switch ($type){
        case 1  : return    '目录'; break;
        case 2  : return    '主题'; break;
        case 3  : return    '段落'; break;
        default : return    false;  break;
    }
}

/**
 * 获取当前文档的分类
 * @param int $id
 * @return array 文档类型数组
 */
function get_cate($cate_id = null){
    if(empty($cate_id)){
        return false;
    }
    $cate   =  Category::model()->findByPk($cate_id);
    return $cate->title;
}

/* 解析列表定义规则*/

function get_list_field($data, $grid,$model){

	// 获取当前字段数据
    foreach($grid['field'] as $field){
        $array  =   explode('|',$field);
        $temp  =	$data[$array[0]];
        // 函数支持
        if(isset($array[1])){
            $temp = call_user_func($array[1], $temp);
        }
        $data2[$array[0]]    =   $temp;
    }
    if(!empty($grid['format'])){
        $value  =   preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data2){return $data2[$match[1]];}, $grid['format']);
    }else{
        $value  =   implode(' ',$data2);
    }

	// 链接支持
	if(!empty($grid['href'])){
		$links  =   explode(',',$grid['href']);
        foreach($links as $link){
            $array  =   explode('|',$link);
            $href   =   $array[0];
            if(preg_match('/^\[([a-z_]+)\]$/',$href,$matches)){
                $val[]  =   $data2[$matches[1]];
            }else{
                $show   =   isset($array[1])?$array[1]:$value;
                // 替换系统特殊字符串
                $href	=	str_replace(
                    array('[DELETE]','[EDIT]','[MODEL]'),
                    array('delete?ids=[id]&model=[MODEL]','edit?id=[id]&model=[MODEL]',$model['id']),
                    $href);

                // 替换数据变量
                $href	=	preg_replace_callback('/\[([a-z_]+)\]/', function($match) use($data){return $data[$match[1]];}, $href);

                $val[]	=	'<a href="'.url('article/'.$href).'">'.$show.'</a>';
            }
        }
        $value  =   implode(' ',$val);
	}
    return $value;
}
/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? NOW_TIME : intval($time);
    return date($format, $time);
}


/**
 * 获取属性信息并缓存
 * @param  integer $id    属性ID
 * @param  string  $field 要获取的字段名
 * @return string         属性信息
 */
function get_model_attribute($model_id, $group = true){
    static $list;

    /* 非法ID */
    if(empty($model_id) || !is_numeric($model_id)){
        return '';
    }

    /* 读取缓存数据 */
    /*if(empty($list)){
        $list = S('attribute_list');
    }*/

    /* 获取属性 */
    if(!isset($list[$model_id])){
        $model = DocModel::model()->findByPk($model_id);
        $extend=$model['extend'];
        $criteria=new CDbCriteria();
        
        if($extend){
            $criteria->addInCondition('model_id', array($model_id,$extend));
        }else{
            $criteria->addCondition('model_id',$model_id);
        }
        $info = Attribute::model()->findAll($criteria);
        $info=findall_to_array($info);
        $list[$model_id] = $info;
        //S('attribute_list', $list); //更新缓存
    }

    $attr = array();
    foreach ($list[$model_id] as $value) {
        $attr[$value['id']] = $value;
    }

    if($group){
        $model = DocModel::model()->findByPk($model_id);
        $sort  = $model->field_sort;

        if(empty($sort)){	//未排序
            $group = array(1=>array_merge($attr));
        }else{
            $group = json_decode($sort, true);

            $keys  = array_keys($group);
            foreach ($group as &$value) {
                foreach ($value as $key => $val) {
                    $value[$key] = $attr[$val];
                    unset($attr[$val]);
                }
            }

            if(!empty($attr)){
                $group[$keys[0]] = array_merge($group[$keys[0]], $attr);
            }
        }
        $attr = $group;
    }
    return $attr;
}

/**
 * 获取当前分类的文档类型
 * @param int $id
 * @return array 文档类型数组
 */
function get_type_bycate($id = null){
    if(empty($id)){
        return false;
    }
    $type_list  =   C('DOCUMENT_MODEL_TYPE');
    $category=Category::model()->findByPk($id);
    $model_type =   $category->type;
    $model_type =   explode(',', $model_type);
    foreach ($type_list as $key=>$value){
        if(!in_array($key, $model_type)){
            unset($type_list[$key]);
        }
    }
    return $type_list;
}

// 分析枚举类型字段值 格式 a:名称1,b:名称2
 // 暂时和 parse_config_attr功能相同
 // 但请不要互相使用，后期会调整
function parse_field_attr($string) {
    if(0 === strpos($string,':')){
        // 采用函数定义
        return   eval(substr($string,1).';');
    }
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if(strpos($string,':')){
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    }else{
        $value  =   $array;
    }
    return $value;
}

//根据field类型返回html
function get_html_by_field_type($field,$data=null,$cate_id=null)
{
    $field['value']=$data[$field['name']];
    switch ($field['type'])
    { 
        case 'num':
            echo ' <input type="text" class="text input-medium" name="'.$field['name'].'" value="'.$field['value'].'">';
            break;
        case 'string':
            echo ' <input type="text" class="text input-large" name="'.$field['name'].'" value="'.$field['value'].'">';
            break;
    
        case 'textarea':
            echo '<label class="textarea input-large">
            <textarea name="'.$field['name'].'">'.$field['value'].'</textarea>
            </label>';
            break;
        case 'datetime':
            echo '<input type="text" name="'.$field['name'].'" class="text input-large time" value="'.date('Y-m-d H:i',$field['value']?$field['value']:time()).'" placeholder="请选择时间" />';
            break;
          
        case 'bool':
            $option='';
            foreach (parse_field_attr($field['extra']) as $key=>$vo)
            {
                $selected=$field['value']==$key?'selected':'';
                $option.='<option value="'.$key.'" '.$selected.'>'.$vo.'</option>';
            }
            echo '<select name="'.$field['name'].'">
                '.$option.'
            </select>';
            break;
        case 'select':
             $option='';
            foreach (parse_field_attr($field['extra']) as $key=>$vo)
            {
                $show='';
                if($field['name']=='type'&&$cate_id)//内容类型需要绑定栏目
                {
                    
                    $cate=Category::model()->findByPk($cate_id);
                    if(strpos($cate['type'],(string)$key)===FALSE)
                    {
                        $show=" disabled";
                    }
                }
                $selected=$field['value']==$key?'selected':'';
                $option.='<option value="'.$key.'" '.$selected.$show.'>'.$vo.'</option>';
            }
            echo '<select name="'.$field['name'].'">
                '.$option.'
            </select>';
            break;
        case 'radio':
            $ret='';
            foreach (parse_field_attr($field['extra']) as $key=>$vo)
            {
                $checked=$field['value']==$key?'checked':'';
                $ret.='<label class="radio">
                <input type="radio" value="'.$key.'" '.$checked.' name="'.$field['name'].'">'.$vo.'
            	</label>';
            }
            echo $ret;
            break;
        case 'checkbox':
            $ret='';
            foreach (parse_field_attr($field['extra']) as $key=>$vo)
            {
                $checked=$field['value']==$key?'checked':'';
                $ret.='<label class="checkbox">
                <input type="checkbox" value="'.$key.'" '.$checked.' name="'.$field['name'].'">'.$vo.'
            	</label>';
            }
            echo $ret;
            break;
        case 'editor':
            echo ' <label class="textarea">
            <textarea name="'.$field['name'].'">'.$field['value'].'</textarea>
            '.newhook('adminArticleEdit',array('name'=>$field['name'],'value'=>$field['value'])).'
            </label>';
            break;
        case 'picture':
            $img=!is_null($field['value'])?'<div class="upload-pre-item"><img src="'.get_cover($field['value']).'"/></div>':'';
            echo '<div class="controls">
    			<input type="file" id="upload_picture_'.$field['name'].'">
    			<input type="hidden" name="'.$field['name'].'" id="cover_id_'.$field['name'].'"/>
    			<div class="upload-img-box">
    			'.$img.'
    			</div>
    		</div>
    		<script type="text/javascript">
    		//上传图片
    	    /* 初始化上传插件 */
    		$("#upload_picture_'.$field['name'].'").uploadify({
    	        "height"          : 30,
    	        "swf"             : "'.Yii::app()->params["main_params"]["static_url"] .'/extendstatic/uploadify/uploadify.swf",
    	        "fileObjName"     : "download",
    	        "buttonText"      : "上传图片",
    	        "uploader"        : "'.url('File/uploadPicture',array('session_id'=>session_id())).'",
    	        "width"           : 120,
    	        "removeTimeout"	  : 1,
    	        "fileTypeExts"	  : "*.jpg; *.png; *.gif;",
    	        "onUploadSuccess" : uploadPicture'.$field['name'].',
    	        "onFallback" : function() {
    	            alert("未检测到兼容版本的Flash.");
    	        }
    	    });
    		function uploadPicture'.$field['name'].'(file, data){
    	    	var data = $.parseJSON(data);
    	    	var src = "";
    	        if(data.status){
    	        	$("#cover_id_'.$field['name'].'").val(data.id);
    	        	src = data.url || "'.getUriTrimIndex().'" + data.path;
    	        	$("#cover_id_'.$field['name'].'").parent().find(".upload-img-box").html(
    	        		"<div class=\"upload-pre-item\"><img src=\" "+ src + "\"/></div>"
    	        	);
    	        } else {
    	        	updateAlert(data.info);
    	        	setTimeout(function(){
    	                $("#top-alert").find("button").click();
    	                $(that).removeClass("disabled").prop("disabled",false);
    	            },1500);
    	        }
    	    }
    		</script>';
            break;
        case 'file':
            $fileinfo=get_table_field($field['value'],'id','','File');
            $file=isset($field['value'])?'<div class="upload-pre-file"><span class="upload_icon_all"></span>'.$fileinfo['name'].'</div>':'';
            $enctype=think_encrypt(json_encode(get_table_field($field['value'],'id','','File')));
            echo '<div class="controls">
    			<input type="file" id="upload_file_'.$field['name'].'">
    			<input type="hidden" name="'.$field['name'].'" value="'.$enctype.'"/>
    			<div class="upload-img-box">
    				'.$file.'
    			</div>
    		</div>
    		<script type="text/javascript">
    		//上传图片
    	    /* 初始化上传插件 */
    		$("#upload_file_'.$field['name'].'").uploadify({
    	        "height"          : 30,
    	         "swf"             : "'.Yii::app()->params["main_params"]["static_url"] .'/extendstatic/uploadify/uploadify.swf",
    	        "fileObjName"     : "download",
    	        "buttonText"      : "上传附件",
    	        "uploader"        : "'.url('File/upload',array('session_id'=>session_id())).'",
    	        "width"           : 120,
    	        "removeTimeout"	  : 1,
    	        "onUploadSuccess" : uploadFile'.$field['name'].',
    	        "onFallback" : function() {
    	            alert("未检测到兼容版本的Flash.");
    	        }
    	    });
    		function uploadFile'.$field['name'].'(file, data){
    			var data = $.parseJSON(data);
    	        if(data.status){
    	        	var name = "'.$field['name'].'";
    	        	$("input[name="+name+"]").val(data.data);
    	        	$("input[name="+name+"]").parent().find(".upload-img-box").html(
    	        		"<div class=\"upload-pre-file\"><span class=\"upload_icon_all\"></span>" + data.info + "</div>"
    	        	);
    	        } else {
    	        	updateAlert(data.info);
    	        	setTimeout(function(){
    	                $("#top-alert").find("button").click();
    	                $(that).removeClass("disabled").prop("disabled",false);
    	            },1500);
    	        }
    	    }
    		</script>';
            break;
            default:
                echo ' <input type="text" class="text input-large" name="'.$field['name'].'" value="'.$field['value'].'">';
       }
}


/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 */
function addons_url($url, $param = array()){
    $url        = parse_url($url);
    $case       = C('URL_CASE_INSENSITIVE');
    $addons     = $case ? $url['scheme'] : $url['scheme'];
    $controller = $case ? parse_name($url['host']) : $url['host'];
    $action     = trim($case ? strtolower($url['path']) : $url['path'], '/');

    /* 解析URL带的参数 */
    if(isset($url['query'])){
        parse_str($url['query'], $query);
        $param = array_merge($query, $param);
    }

    /* 基础参数 */
    $params = array(
        '_addons'     => $addons,
        '_controller' => $controller,
        '_action'     => $action,
    );
    $params = array_merge($params, $param); //添加额外参数

    return url('winstonaddons_'.$controller.'/'.$action, $params);
}
/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}


/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 */
function think_encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 */
function think_decrypt($data, $key = ''){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);

    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

/**
 * 根据用户ID获取用户信息
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_memberinfo($uid = 0,$field=NULL){
    if(!($uid && is_numeric($uid))){ //获取当前登录用户名
        return getSession('user_auth.username');
    }
    $member=Member::model()->findByPk($uid);
    if(!is_null($field))
    {
        return $member[$field];
    }
    return $member;
}


/**
 * 获取数据的所有子孙数据的id值
 */

function get_stemma($pids, &$model, $field='id'){
    $collection = array();

    //非空判断
    if(empty($pids)){
        return $collection;
    }

    if( is_array($pids) ){
        $pids = trim(implode(',',$pids),',');
    }
    $result     = $model->find(
        array(
            'condition'=>'pid in ('.(string)$pids.')'
        )
    );
    $result=findall_to_array($result);
    $child_ids  = array_column ((array)$result,'id');

    while( !empty($child_ids) ){
        $collection = array_merge($collection,$result);
        $result     = $model->find(
            array(
                'condition'=>'pid in ('.(string)$child_ids.')'
            )
        );
        $result=findall_to_array($result);
        $child_ids  = array_column((array)$result,'id');
    }
    return $collection;
}

  //获取model信息 ajax提示
function get_model_ajax_error($model)
    {
        $errors=$model->getErrors();
        $str="";
        foreach($errors as $k=>$v)
        {
            $str.=$v[0];
        }
        return $str;
    }
    
    
/**
 * 根据条件字段获取指定表的数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @param string $table 需要查询的表
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null){
    if(empty($value) || empty($table)){
        return false;
    }
    $criteria=new CDbCriteria();
    //拼接参数
    $criteria->addCondition("$condition='$value'");
    $model=ucfirst($table);
    $model=new $model;
    $info=$model->find($criteria);
    $info=findall_to_array($info);
    return $info;
}

// 获取模型名称
function get_model_by_id($id){
    $model = DocModel::model()->findByPk($id);
    return $model->title;
}

// 获取属性类型信息
function get_attribute_type($type=''){
    // TODO 可以加入系统配置
    static $_type = array(
        'num'       =>  array('数字','int(10) UNSIGNED NOT NULL'),
        'string'    =>  array('字符串','varchar(255) NOT NULL'),
        'textarea'  =>  array('文本框','text NOT NULL'),
        'datetime'  =>  array('时间','int(10) NOT NULL'),
        'bool'      =>  array('布尔','tinyint(2) NOT NULL'),
        'select'    =>  array('枚举','char(50) NOT NULL'),
    	'radio'		=>	array('单选','char(10) NOT NULL'),
    	'checkbox'	=>	array('多选','varchar(100) NOT NULL'),
    	'editor'    =>  array('编辑器','text NOT NULL'),
    	'picture'   =>  array('上传图片','int(10) UNSIGNED NOT NULL'),
    	'file'    	=>  array('上传附件','int(10) UNSIGNED NOT NULL'),
    );
    return $type?$_type[$type][0]:$_type;
}

//获取版本
function get_yiicms_version()
{
    return file_get_contents(YII::app()->basePath.'/config/version.txt');
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type=0){
    $list = C('CONFIG_TYPE_LIST');
    return $list[$type];
}

/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group=0){
    $list = C('CONFIG_GROUP_LIST');
    return $group?$list[$group]:'';
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){
    return $name;
}
/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
//仿TP钩子
function newhook($hook,$params=array())
{
    Hook::listen($hook,$params);
}

//基于数组创建目录和文件
function create_dir_or_files($files){
    foreach ($files as $key => $value) {
        if(substr($value, -1) == '/'){
            mkdir($value);
        }else{
            @file_put_contents($value, '');
        }
    }
}

//设置插件动态生成html
function set_addons_config($form,$o_key)
{
        switch ($form['type']){
					    case "text":
					        echo '<div class="controls">
								<input type="text" name="config['.$o_key.']" class="text input-large" value="'.$form['value'].'">
							</div>';
					        break;
					    case "password":
					        echo '<div class="controls">
								<input type="password" name="config['.$o_key.']" class="text input-large" value="'.$form['value'].'">
							</div>';
					        break;
					    case "hidden":
					        echo '<input type="hidden" name="config['.$o_key.']" value="'.$form['value'].'">';
					        break;
					    case "radio":
					        echo '<div class="controls">';
					        foreach ($form['options'] as $opt_k=>$opt)
					        {
					            $checked=$form['value']==$opt_k?"checked":'';
					         
					            echo '<label class="radio">
										<input type="radio" name="config['.$o_key.']" value="'.$opt_k.'" 
										'.$checked.'>'.$opt.'
									</label>';
					        }
					        echo '</div>';
					        break;
					    case "checkbox":
					        echo '<div class="controls">';
					        foreach ($form['options'] as $opt_k=>$opt)
					        {
					            $checked='';
					            is_null($form["value"]) && $form["value"] = array();
					            if(in_array(($opt_k), $form['value'])){
                                   $checked="checked";
                                }
                                echo '<input type="checkbox" name="config['.$o_key.'][]" value="'.$opt_k.'" '.$checked.'>'.$opt.'
									';
					        }
					        echo '</div>';
					        break;
					    case "select":
					         echo '<div class="controls">';
					         echo '<select name="config['.$o_key.']">';
					          foreach ($form['options'] as $opt_k=>$opt)
					          {
					              $checked=$form['value']==$opt_k?"selected":'';
					              echo '<option value="'.$opt_k.'" '.$checked.'>'.$opt.'</option>';
					          }
					         echo '</select>';
					         echo '</div>';
					        break;
					    case "textarea":
					        echo '<div class="controls">';
					        echo '<label class="textarea input-large">
									<textarea name="config['.$o_key.']">'.$form['value'].'</textarea>
								</label>';
					        echo '</div>';
					        break;
					    case "picture_union":
					        if(!empty($form['value']))
					        {
					            
					           $mulimages = explode(",", $form["value"]);
					           foreach ($mulimages as $one)
					           {
					               $img.='<div class="upload-pre-item" val="'.$one.'">
											<img src="'.get_cover($one).'"  ondblclick="removePicture'.$o_key.'(this)"/>
										</div>';
					           }
					        }
					        
					       
					        echo '<div class="controls">
                			<input type="file" id="upload_picture_'.$o_key.'">
                			<input type="hidden" name="'.$o_key.'" id="cover_id_'.$o_key.'" value="'.$form['value'].'"/>
                			<div class="upload-img-box">
                			'.$img.'
                			</div>
                		</div>
                		<script type="text/javascript">
                		//上传图片
                	    /* 初始化上传插件 */
                		$("#upload_picture_'.$o_key.'").uploadify({
                	        "height"          : 30,
                	        "swf"             : "'.Yii::app()->params["main_params"]["static_url"] .'/extendstatic/uploadify/uploadify.swf",
                	        "fileObjName"     : "download",
                	        "buttonText"      : "上传图片",
                	        "uploader"        : "'.url('File/uploadPicture',array('session_id'=>session_id())).'",
                	        "width"           : 120,
                	        "removeTimeout"	  : 1,
                	        "fileTypeExts"	  : "*.jpg; *.png; *.gif;",
                	        "onUploadSuccess" : uploadPicture'.$o_key.',
                	        "onFallback" : function() {
                	            alert("未检测到兼容版本的Flash.");
                	        }
                	    });
                		function uploadPicture'.$o_key.'(file, data){
                	    	var data = $.parseJSON(data);
                	    	var src = "";
                	        if(data.status){
                	        	$("#cover_id_'.$o_key.'").val(data.id);
                	        	src = data.url || "'.getUriTrimIndex().'" + data.path;
                	        	$("#cover_id_'.$o_key.'").parent().find(".upload-img-box").html(
                	        		"<div class=\"upload-pre-item\"><img src=\" "+ src + "\"/></div>"
                	        	);
                	        } else {
                	        	updateAlert(data.info);
                	        	setTimeout(function(){
                	                $("#top-alert").find("button").click();
                	                $(that).removeClass("disabled").prop("disabled",false);
                	            },1500);
                	        }
                	    }
                	    function removePicture{'.$o_key.'}(o){
										var p = $(o).parent().parent();
										$(o).parent().remove();
										setPictureIds{'.$o_key.'}();
									}
									function setPictureIds{'.$o_key.'}(){
										var ids = [];
										$("#cover_id_'.$o_key.'").parent().find(\'.upload-img-box\').find(\'.upload-pre-item\').each(function(){
											ids.push($(this).attr(\'val\'));
										});
										if(ids.length > 0)
											$("#cover_id_'.$o_key.'").val(ids.join(\',\'));
										else
											$("#cover_id_'.$o_key.'").val(\'\');
									}
                		</script>';
					        break;
					    case "group":
					        echo '<ul class="tab-nav nav">';
					        foreach ($form['options'] as $i=>$li)
					        {
					            $current=$i==1?'current':'';
					            echo '<li data-tab="tab'.$i.'" '.$current.'><a href="javascript:void(0);">'.$li['title'].'</a></li>
									';
					        }
									
							echo '</ul>';
							echo '<div class="tab-content">';
							
							foreach ($form['options'] as $i=>$tab)
							{
							    $in=$i==1?'in':'';
							    echo '<div id="tab'.$i.'" class="tab-pane '.$in.' tab'.$i.'">';
							    foreach ($tab['options'] as $o_tab_key=>$tab_form)
							    {
							        echo '<label class="item-label">'.$tab_form['title'].'
											
												<span class="check-tips">'.isset($tab_form['tip'])?$tab_form['tip']:''.'</span>
											
										</label>';
							        echo '<div class="controls">';
							        set_addons_config($tab_form, $o_tab_key);
							        echo '</div>';
							    }
							    echo '</div>';
							}
							echo '</div>';
				        }
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array

 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
function arr2str($arr, $glue = ','){
    return implode($glue, $arr);
}


/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0){
    if(!($uid && is_numeric($uid))){ //获取当前登录用户名
        return getSession('user_auth.username');
    }
        //调用接口获取用户信息
        $info = Member::model()->findByPk($uid);
        if($info !== false && $info['nickname'] ){
            $nickname = $info['nickname'];
            $name = $nickname;
           
        } else {
            $name = '';
        }
    return $name;
}
