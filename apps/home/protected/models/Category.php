<?php

/**
 * This is the model class for table "{{category}}".
 *
 * The followings are the available columns in table '{{category}}':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $pid
 * @property string $sort
 * @property integer $list_row
 * @property string $meta_title
 * @property string $keywords
 * @property string $description
 * @property string $template_index
 * @property string $template_lists
 * @property string $template_detail
 * @property string $template_edit
 * @property string $model
 * @property string $type
 * @property string $link_id
 * @property integer $allow_publish
 * @property integer $display
 * @property integer $reply
 * @property integer $check
 * @property string $reply_model
 * @property string $extend
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property string $icon
 */
class Category extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{category}}';
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
			array('list_row, allow_publish, display, reply, check, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('title, meta_title', 'length', 'max'=>50),
			array('pid, sort, link_id, create_time, update_time, icon', 'length', 'max'=>10),
			array('keywords, description', 'length', 'max'=>255),
			array('template_index, template_lists, template_detail, template_edit, model, type, reply_model', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, title, pid, sort, list_row, meta_title, keywords, description, template_index, template_lists, template_detail, template_edit, model, type, link_id, allow_publish, display, reply, check, reply_model, extend, create_time, update_time, status, icon', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'sort' => 'Sort',
			'list_row' => 'List Row',
			'meta_title' => 'Meta Title',
			'keywords' => 'Keywords',
			'description' => 'Description',
			'template_index' => 'Template Index',
			'template_lists' => 'Template Lists',
			'template_detail' => 'Template Detail',
			'template_edit' => 'Template Edit',
			'model' => 'Model',
			'type' => 'Type',
			'link_id' => 'Link',
			'allow_publish' => 'Allow Publish',
			'display' => 'Display',
			'reply' => 'Reply',
			'check' => 'Check',
			'reply_model' => 'Reply Model',
			'extend' => 'Extend',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'icon' => 'Icon',
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
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('list_row',$this->list_row);
		$criteria->compare('meta_title',$this->meta_title,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('template_index',$this->template_index,true);
		$criteria->compare('template_lists',$this->template_lists,true);
		$criteria->compare('template_detail',$this->template_detail,true);
		$criteria->compare('template_edit',$this->template_edit,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('link_id',$this->link_id,true);
		$criteria->compare('allow_publish',$this->allow_publish);
		$criteria->compare('display',$this->display);
		$criteria->compare('reply',$this->reply);
		$criteria->compare('check',$this->check);
		$criteria->compare('reply_model',$this->reply_model,true);
		$criteria->compare('extend',$this->extend,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('icon',$this->icon,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
 	/**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     */
    public function getTree($id = 0, $field = ''){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->info($id);
            $id   = $info['id'];
        }

        /* 获取所有分类 */
        $criteria=new CDbCriteria();
        if(!empty($field))
        {
            $criteria->select=$field;
        }
        $criteria->condition='status>-1';
        $criteria->order='sort asc';
        $list = $this->findAll($criteria);
        $list=findall_to_array($list);
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

        return $info;
    }
    /**
     * 获取分类详细信息
     * @param  milit   $id 分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     分类信息
     */
    public function info($id, $field = ''){
        /* 获取分类信息 */
        $criteria=new CDbCriteria();
        if(!empty($field))
        {
            $criteria->select=$field;
        }
        if(is_numeric($id)){ //通过ID查询
            $criteria->addCondition('id='.$id);
        } else { //通过标识查询
            $criteria->addCondition('name='.$id);
        }
        $info= $this->find($criteria);
        $info=findall_to_array($info);
        $info['model']=explode(',', $info['model']);
        $info['type']=explode(',', $info['type']);
       
        return $info;
    }
	/**
	 * 获取指定分类子分类ID
	 * @param  string $cate 分类ID
	 * @return string       id列表
	 */
	public function getChildrenId($cate){
		$field = 'id,name,pid,title,link_id';
		$category = Category::model()->getTree($cate, $field);
		$ids = array();
		foreach ($category['_'] as $key => $value) {
			$ids[] = $value['id'];
		}
		return implode(',', $ids);
	}
}
