<?php

/**
 * This is the model class for table "{{document}}".
 *
 * The followings are the available columns in table '{{document}}':
 * @property string $id
 * @property string $uid
 * @property string $name
 * @property string $title
 * @property string $category_id
 * @property string $description
 * @property string $root
 * @property string $pid
 * @property integer $model_id
 * @property integer $type
 * @property integer $position
 * @property string $link_id
 * @property string $cover_id
 * @property integer $display
 * @property string $deadline
 * @property integer $attach
 * @property string $view
 * @property string $comment
 * @property string $extend
 * @property integer $level
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 */
class Document extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{document}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category_id', 'required'),
			array('model_id, type, position, display, attach, level, status', 'numerical', 'integerOnly'=>true),
			array('uid, category_id, root, pid, link_id, cover_id, deadline, view, comment, extend, create_time, update_time', 'length', 'max'=>10),
			array('name', 'length', 'max'=>40),
			array('title', 'length', 'max'=>80),
			array('description', 'length', 'max'=>140),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, name, title, category_id, description, root, pid, model_id, type, position, link_id, cover_id, display, deadline, attach, view, comment, extend, level, create_time, update_time, status', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'name' => 'Name',
			'title' => 'Title',
			'category_id' => 'Category',
			'description' => 'Description',
			'root' => 'Root',
			'pid' => 'Pid',
			'model_id' => 'Model',
			'type' => 'Type',
			'position' => 'Position',
			'link_id' => 'Link',
			'cover_id' => 'Cover',
			'display' => 'Display',
			'deadline' => 'Deadline',
			'attach' => 'Attach',
			'view' => 'View',
			'comment' => 'Comment',
			'extend' => 'Extend',
			'level' => 'Level',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
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
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('root',$this->root,true);
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('position',$this->position);
		$criteria->compare('link_id',$this->link_id,true);
		$criteria->compare('cover_id',$this->cover_id,true);
		$criteria->compare('display',$this->display);
		$criteria->compare('deadline',$this->deadline,true);
		$criteria->compare('attach',$this->attach);
		$criteria->compare('view',$this->view,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('extend',$this->extend,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Document the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    
/**
	 * 获取文档列表
	 * @param  integer  $category 分类ID
	 * @param  string   $order    排序规则
	 * @param  integer  $status   状态
	 * @param  boolean  $count    是否返回总数
	 * @param  string   $field    字段 true-所有字段
	 * @return array              文档列表
	 */
	public function lists($category, $order = '`id` DESC', $status = 1, $field = true){
		$criteria = $this->listMap($category, $status);
		$count=$this->count($criteria);
        $pages=new CPagination($count);
        $pages->pageSize=C('PAGE_SIZE');
        $pages->applyLimit($criteria);
        
        $criteria->limit=$pages->pageSize;
        $criteria->offset=$pages->currentPage*$pages->pageSize;
        $lists=$this->findAll($criteria);
        
        $this->pages=$pages;
        $this->total=$count;
        return int_to_string(findall_to_array($lists));
	}
	
	
/**
	 * 设置where查询条件
	 * @param  number  $category 分类ID
	 * @param  number  $pos      推荐位
	 * @param  integer $status   状态
	 * @return array             查询条件
	 */
	private function listMap($category, $status = 1, $pos = null){
	    
	    $criteria=new CDbCriteria();
	    
		/* 设置状态 */
		$criteria->condition='status='.$status.' and pid=0';

		/* 设置分类 */
		if(!is_null($category)){
			if(is_numeric($category)){
			    $criteria->addCondition('category_id='.$category);
				
			} else {
			    $criteria->addInCondition('category_id',str2arr($category));
			}
		}


		$criteria->addCondition('create_time<='.time());
		$criteria->addCondition('deadline = 0 OR deadline > ' . time());
	
		/* 设置推荐位 */
		if(is_numeric($pos)){
			//$map[] = "position & {$pos} = {$pos}";
		}

		return $criteria;
	}
}
