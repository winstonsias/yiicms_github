<?php

/**
 * This is the model class for table "{{model}}".
 *
 * The followings are the available columns in table '{{model}}':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $extend
 * @property string $relation
 * @property integer $need_pk
 * @property string $field_sort
 * @property string $field_group
 * @property string $attribute_list
 * @property string $template_list
 * @property string $template_add
 * @property string $template_edit
 * @property string $list_grid
 * @property integer $list_row
 * @property string $search_key
 * @property string $search_list
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $engine_type
 */
class DocModel extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{model}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, title', 'required'),
			array('need_pk, list_row, status', 'numerical', 'integerOnly'=>true),
			array('name, title, relation', 'length', 'max'=>30),
			array('extend, create_time, update_time', 'length', 'max'=>10),
			array('field_group, search_list', 'length', 'max'=>255),
			array('template_list, template_add, template_edit', 'length', 'max'=>100),
			array('search_key', 'length', 'max'=>50),
			array('engine_type', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, extend, relation, need_pk, field_sort, field_group, attribute_list, template_list, template_add, template_edit, list_grid, list_row, search_key, search_list, create_time, update_time, status, engine_type', 'safe', 'on'=>'search'),
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
			'extend' => 'Extend',
			'relation' => 'Relation',
			'need_pk' => 'Need Pk',
			'field_sort' => 'Field Sort',
			'field_group' => 'Field Group',
			'attribute_list' => 'Attribute List',
			'template_list' => 'Template List',
			'template_add' => 'Template Add',
			'template_edit' => 'Template Edit',
			'list_grid' => 'List Grid',
			'list_row' => 'List Row',
			'search_key' => 'Search Key',
			'search_list' => 'Search List',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'engine_type' => 'Engine Type',
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
		$criteria->compare('extend',$this->extend,true);
		$criteria->compare('relation',$this->relation,true);
		$criteria->compare('need_pk',$this->need_pk);
		$criteria->compare('field_sort',$this->field_sort,true);
		$criteria->compare('field_group',$this->field_group,true);
		$criteria->compare('attribute_list',$this->attribute_list,true);
		$criteria->compare('template_list',$this->template_list,true);
		$criteria->compare('template_add',$this->template_add,true);
		$criteria->compare('template_edit',$this->template_edit,true);
		$criteria->compare('list_grid',$this->list_grid,true);
		$criteria->compare('list_row',$this->list_row);
		$criteria->compare('search_key',$this->search_key,true);
		$criteria->compare('search_list',$this->search_list,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('engine_type',$this->engine_type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    //自动完成
	public function afterValidate()
	{
	    parent::afterValidate();
	    if($this->isNewRecord)
	    {
	        $this->field_sort=$this->getFields($this->field_sort);
	        $this->name=strtolower($this->name);
    	    $this->create_time=NOW_TIME;
    	    $this->update_time=NOW_TIME;
    	    $this->status=$this->status?$this->status:1;
	    }else
	    {
	        $this->update_time=NOW_TIME;
	    }
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Model the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function winston_update(){
        /* 获取数据对象 */
       $data = empty($data) ? $_POST : $data;
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $model=new DocModel();
            $model->attributes=$data;
            
            if($model->validate())
            {
                 $status=$model->save();
                 $id = $model->id;
            }else
            {
                $this->myerror = '新增模型出错！'.get_model_ajax_error($model);
                return false;
            }
            if(!$status){
                $this->myerror = '新增模型出错！'.get_model_ajax_error($model);
                return false;
            }
            
           
        } else { //更新数据
            if(!isset($data['field_sort']))
            {
                $this->myerror = '列表定义不能为空';
                return false;
            }
            $data['field_sort']=$this->getFields($data['field_sort']);
            $data['update_time']=NOW_TIME;
            $status = $this->updateByPk($data['id'], $data); //更新基础内容
            if(false === $status){
                $this->myerror = '更新模型出错！';
                return false;
            }
        }
		// 清除模型缓存数据

        //内容添加或更新完成
        return $data;
    }
    
    
 	/**
     * 处理字段排序数据
     */
    protected function getFields($fields){
    	return empty($fields) ? '' : json_encode($fields);
    }
    
    
    /**
     * 获取指定数据库的所有表名
     */
    public function getTables($connection = null){
    	$tables = app()->db->createCommand('SHOW TABLES;')->queryAll();
    	$constr=$this->getDbConnection()->connectionString;
    	$dbname='';
    	
	    $constrarr=explode('dbname=',$constr);
	    $dbname=$constrarr[1];
    	
    	foreach ($tables as $key=>$value){
    		$tables[$key] = $value['Tables_in_'.$dbname];
    	}
    	return $tables;
    }
    
    
 /**
     * 根据数据表生成模型及其属性数据
     */
    public function generate($table){
    	//新增模型数据
    	$name = substr($table, strlen(app()->db->tablePrefix));
    	$data = array('name'=>$name, 'title'=>$name);
    	$model=new DocModel();
    	$model->attributes=$data;

    	$res = $model->save();
    	$res=$model->id;
    	if(!$res){
    	    $this->myerror=get_model_ajax_error($model);
    		return false;
    	}

    	//新增属性
		$fields = app()->db->createCommand('SHOW FULL COLUMNS FROM '.$table)->queryAll();
		foreach ($fields as $key=>$value){
			//不新增id字段
			if(strcmp($value['Field'], 'id') == 0){
				continue;
			}

			//生成属性数据
			$data = array();
			$data['name'] = $value['Field'];
			$data['title'] = $value['Comment'];
			$data['type'] = 'string';	//TODO:根据字段定义生成合适的数据类型
			//获取字段定义
			$is_null = strcmp($value['Null'], 'NO') == 0 ? ' NOT NULL ' : ' NULL ';
			$data['field'] = $value['Type'].$is_null;
			$data['value'] = $value['Default'] == null ? '' : $value['Default'];
			$data['model_id'] = $res;

			Attribute::model()->winston_update($data, false);
		}
    	return $res;
    }
    
	/**
     * 删除一个模型
     * @param integer $id 模型id
     */
    public function del($id){
    	//获取表名
    	$model = $this->findByPk($id);
    	$table_name = app()->db->tablePrefix.strtolower($model['name']);
    	//删除属性数据
        Attribute::model()->deleteAll('model_id='.$id);
    	//删除模型数据
    	$this->deleteByPk($id);
    	//删除该表
    	/*$sql = <<<sql
				DROP TABLE {$table_name};
sql;
    	$res = app()->db->createCommand($sql)->execute();*/
    	return true;
    }
}
