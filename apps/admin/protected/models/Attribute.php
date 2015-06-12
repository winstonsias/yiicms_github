<?php

/**
 * This is the model class for table "{{attribute}}".
 *
 * The followings are the available columns in table '{{attribute}}':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $field
 * @property string $type
 * @property string $value
 * @property string $remark
 * @property integer $is_show
 * @property string $extra
 * @property string $model_id
 * @property integer $is_must
 * @property integer $status
 * @property string $update_time
 * @property string $create_time
 * @property string $validate_rule
 * @property integer $validate_time
 * @property string $error_info
 * @property string $validate_type
 * @property string $auto_rule
 * @property integer $auto_time
 * @property string $auto_type
 */
class Attribute extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{attribute}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('field,name,model_id,type','required'),
			array('is_show, is_must, status, validate_time, auto_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('title, field, value, remark, error_info, auto_rule', 'length', 'max'=>100),
			array('type', 'length', 'max'=>20),
			array('extra, validate_rule', 'length', 'max'=>255),
			array('model_id', 'length', 'max'=>10),
			array('update_time, create_time', 'length', 'max'=>11),
			array('validate_type, auto_type', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, field, type, value, remark, is_show, extra, model_id, is_must, status, update_time, create_time, validate_rule, validate_time, error_info, validate_type, auto_rule, auto_time, auto_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'title' => 'Title',
			'field' => 'Field',
			'type' => 'Type',
			'value' => 'Value',
			'remark' => 'Remark',
			'is_show' => 'Is Show',
			'extra' => 'Extra',
			'model_id' => 'Model',
			'is_must' => 'Is Must',
			'status' => 'Status',
			'update_time' => 'Update Time',
			'create_time' => 'Create Time',
			'validate_rule' => 'Validate Rule',
			'validate_time' => 'Validate Time',
			'error_info' => 'Error Info',
			'validate_type' => 'Validate Type',
			'auto_rule' => 'Auto Rule',
			'auto_time' => 'Auto Time',
			'auto_type' => 'Auto Type',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('field',$this->field,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('is_show',$this->is_show);
		$criteria->compare('extra',$this->extra,true);
		$criteria->compare('model_id',$this->model_id,true);
		$criteria->compare('is_must',$this->is_must);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('validate_rule',$this->validate_rule,true);
		$criteria->compare('validate_time',$this->validate_time);
		$criteria->compare('error_info',$this->error_info,true);
		$criteria->compare('validate_type',$this->validate_type,true);
		$criteria->compare('auto_rule',$this->auto_rule,true);
		$criteria->compare('auto_time',$this->auto_time);
		$criteria->compare('auto_type',$this->auto_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Attribute the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    //自动完成
	public function afterValidate()
	{
	    parent::afterValidate();
	    if($this->isNewRecord)
	    {
    	    $this->create_time=NOW_TIME;
    	    $this->update_time=NOW_TIME;
    	    $this->status=$this->status?$this->status:1;
	    }else
	    {
	        $this->update_time=NOW_TIME;
	    }
	}
	
	/* 操作的表名 */
    protected $table_name = null;
	
/**
     * 新增或更新一个属性
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function winston_update($data = null, $create = true){
        /* 获取数据对象 */
    	$data = empty($data) ? $_POST : $data;
        
        if(empty($data)){
            return false;
        }
        
        /* 添加或新增属性 */
        if(empty($data['id'])){ //新增属性
            $model=new Attribute();
            $model->attributes=$data;
            if($model->validate())
            {
                 $status=$model->save();
                 $id = $model->id;
            }else
            {
                $this->myerror = '新增属性出错！'.get_model_ajax_error($model);
                return false;
            }
            if(!$status){
                $this->myerror = '新增属性出错！'.get_model_ajax_error($model);
                return false;
            }

            if($create){
            	//新增表字段
            	$res = $this->addField($data);
            	if(!$res){
            		$this->myerror = '新建字段出错！';
            		//删除新增数据
            		$this->deleteByPk($id);
            		return false;
            	}
            }

        } else { //更新数据
        	if($create){
        	//更新表字段
	        	$res = $this->updateField($data);
	        	if(!$res){
	        		$this->myerror = '更新字段出错！';
	        		return false;
	        	}
        	}
        	$data['update_time']=NOW_TIME;
            $status = $this->updateByPk($data['id'], $data);
            if(false === $status){
                $this->myerror = '更新属性出错！';
                return false;
            }
        }
      
        //内容添加或更新完成
        return $data;

    }
    
    
/**
     * 新建表字段
     * @param array $field 需要新建的字段属性
     * @return boolean true 成功 ， false 失败
     */
    protected function addField($field){
    	//检查表是否存在
    	$table_exist = $this->checkTableExist($field['model_id']);

    	//获取默认值
    	if($field['value'] === ''){
    		$default = '';
    	}elseif (is_numeric($field['value'])){
    		$default = ' DEFAULT '.$field['value'];
    	}elseif (is_string($field['value'])){
    		$default = ' DEFAULT \''.$field['value'].'\'';
    	}else {
    		$default = '';
    	}

    	if($table_exist){
    		$sql = <<<sql
				ALTER TABLE `{$this->table_name}`
ADD COLUMN `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}';
sql;
    	}else{
    		//新建表时是否默认新增“id主键”字段
    		$model_info = DocModel::model()->findByPk($field['model_id']);
    		if($model_info['need_pk']){
    			$sql = <<<sql
				CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
				`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' ,
				`{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}' ,
				PRIMARY KEY (`id`)
				)
				ENGINE={$model_info['engine_type']}
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;
sql;
    		}else{
    			$sql = <<<sql
				CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
				`{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}'
				)
				ENGINE={$model_info['engine_type']}
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;
sql;
    		}

    	}
    	$connection = Yii::app()->db;
    	$command = $connection->createCommand($sql);
    	$res = $command->execute();
    	return $res !== false;
    }
    
    
/**
     * 检查当前表是否存在
     * @param intger $model_id 模型id
     * @return intger 是否存在
     */
    protected function checkTableExist($model_id){
    	$Model = new DocModel();
    	//当前操作的表
		$model=$Model->findByPk($model_id);

		if($model['extend'] == 0){	//独立模型表名
			$table_name = $this->table_name = app()->db->tablePrefix.strtolower($model['name']);
		}else{						//继承模型表名
			$extend_model=$Model->findByPk($model['extend']);
			$table_name = $this->table_name = app()->db->tablePrefix.strtolower($extend_model['name']).'_'.strtolower($model['name']);
		}
		$sql = <<<sql
				SHOW TABLES LIKE '{$table_name}';
sql;
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
		$res = $command->queryAll();
		return count($res);
    }
    
     /**
     * 更新表字段
     * @param array $field 需要更新的字段属性
     * @return boolean true 成功 ， false 失败
     */
    protected function updateField($field){
    	//检查表是否存在
    	$table_exist = $this->checkTableExist($field['model_id']);

    	//获取原字段名
    	$last_field = $this->findByPk($field['id']);
        $last_field=$last_field->name;
    	//获取默认值
    	$default = $field['value']!='' ? ' DEFAULT "'.$field['value'].'"' : '';

    	$sql = <<<sql
			ALTER TABLE `{$this->table_name}`
CHANGE COLUMN `{$last_field}` `{$field['name']}`  {$field['field']} {$default} COMMENT '{$field['title']}' ;
sql;
    	$connection = Yii::app()->db;
    	$command = $connection->createCommand($sql);
    	$res = $command->execute();
    	return $res !== false;
    }
    
    //自定义删除
    public function winston_delete($id)
    {
        $info = $this->findByPk($id);
        if(empty($info)){
             $this->myerror='该字段不存在！';
             return FALSE;   
        } 
        if($this->deleteByPk($id))
        {
            $this->deleteField($info);
            return true;
        }else
        {
            $this->myerror='删除失败';
            return false;
        }
    }
 /**
     * 删除一个字段
     * @param array $field 需要删除的字段属性
     * @return boolean true 成功 ， false 失败
     */
    protected  function deleteField($field){
    	//检查表是否存在
    	$table_exist = $this->checkTableExist($field['model_id']);

    	$sql = <<<sql
			ALTER TABLE `{$this->table_name}`
DROP COLUMN `{$field['name']}`;
sql;
    	$connection = Yii::app()->db;
    	$command = $connection->createCommand($sql);
    	$res = $command->execute();
    	return $res !== false;
    }
    
    
    
}
