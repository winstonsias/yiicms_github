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
	//自动完成
	public function afterValidate()
	{
	    parent::afterValidate();
	    if($this->isNewRecord)
	    {
    	    $this->create_time=NOW_TIME;
    	    $this->update_time=NOW_TIME;
    	    $this->uid=UID;
    	    $this->status=$this->status?$this->status:1;
	    }else
	    {
	        $this->update_time=NOW_TIME;
	    }
	}

	  /**
     * 验证分类是否允许发布内容
     * @param  integer $id 分类ID
     * @return boolean     true-允许发布内容，false-不允许发布内容
     */
    public function checkCategory($id){
        $publish = get_category($id, 'allow_publish');
        return $publish ? true : false;
    }
/**
     * 新增或更新一个文档
     * @param array  $data 手动传入的数据
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function update($data = null){
    	/* 检查文档类型是否符合要求 */
    	$res = $this->checkDocumentType( $data['type'], $data['pid'] );
    	if(!$res['status']){
    		$this->myerror = $res['info'];
    		return false;
    	}

        /* 获取数据对象 */
        if(empty($data)){
            return false;
        }
        $this->attributes=$data;
    
        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            
            $id = $this->save(); //添加基础内容
            $id=$this->id;//获取最后插入的id值
            if(!$id){
                $this->myerror = '新增基础内容出错！'.get_model_ajax_error($this);;
                return false;
            }
        } else { //更新数据
            $id=$data['id'];
            $data['create_time']=strtotime($data['create_time']);
            $data['deadline']=strtotime($data['deadline']);
            $status = $this->updateByPk($id, $data); //更新基础内容
            if(false === $status){
                $this->myerror = '更新基础内容出错！'.get_model_ajax_error($this);
                return false;
            }
        }

        /* 添加或新增扩展内容 */
        $logic = $this->logic($data['model_id']);
        if(!$logic->winston_update($id)){
            if(isset($id)){ //新增失败，删除基础数据
                //$this->deleteByPk($id);
            }
            $this->myerror = $logic->getMyError();
            return false;
        }

        //hook('documentSaveComplete', array('model_id'=>$data['model_id']));

       
        //内容添加或更新完成
        return $data;
    }
    
	/**
     * 检查指定文档下面子文档的类型
     * @param intger $type 子文档类型
     * @param intger $pid 父文档类型
     * @return array 键值：status=>是否允许（0,1），'info'=>提示信息
     */
    public function checkDocumentType($type = null, $pid = null){
    	$res = array('status'=>1, 'info'=>'');
		if(empty($type)){
			return array('status'=>0, 'info'=>'文档类型不能为空');
		}
		if(empty($pid)){
			return $res;
		}
		//查询父文档的类型
		if(is_numeric($pid)){
		    $document=$this->findByPk($pid);
		}else{
		    $document=$this->findByAttributes(array('name'=>$pid));
		}
		$ptype = $document['type'];
		//父文档为目录时
		if($ptype == 1){
			return $res;
		}
		//父文档为主题时
		if($ptype == 2){
			if($type != 3){
				return array('status'=>0, 'info'=>'主题下面只允许添加段落');
			}else{
				return $res;
			}
		}
		//父文档为段落时
		if($ptype == 3){
			return array('status'=>0, 'info'=>'段落下面不允许再添加子内容');
		}
		return array('status'=>0, 'info'=>'父文档类型不正确');
    }
    
  /**
     * 获取扩展模型对象
     * @param  integer $model 模型编号
     * @return object         模型对象
     */
    private function logic($model){
        $m=get_document_model($model,'name');
        $documentmodel="Document".ucfirst($m);
        return new $documentmodel;
    }
    
    
    
/**
     * 删除状态为-1的数据（包含扩展模型）
     * @return true 删除成功， false 删除失败
     * @author huajie <banhuajie@163.com>
     */
    public function remove(){
        $criteria=new CDbCriteria();
        $criteria->addCondition('status='.DELETE_VAL);
        //查询假删除的基础数据
        if ( !is_administrator() ) {
            $cate_ids = AuthGroup::getAuthCategories(UID);
            $categoryidarr=trim(implode(',',$cate_ids),',');
            $criteria->addInCondition('category_id', $categoryidarr);
        }
        $base_list = $this->findAll($criteria);
        $base_list=findall_to_array($base_list);
        $res=FALSE;
        if(!empty($base_list))
        {
            //删除扩展模型数据
            $base_ids = array_column($base_list,'id');
            //孤儿数据
            $orphan   = get_stemma( $base_ids,$this, 'id,model_id');
    
            $all_list  = array_merge( $base_list,$orphan );
            foreach ($all_list as $key=>$value){
                $logic = $this->logic($value['model_id']);
                $logic->deleteByPk($value['id']);
            }
    
            //删除基础数据
            $ids = array_merge( $base_ids, (array)array_column($orphan,'id') );
            if(!empty($ids)){
                $ids=trim(implode(',',$ids),',');
                $res=$this->deleteAll("id in ($ids)");
            	
            }
        }

        return $res;
    }
    
    
    
	/**
     * 保存为草稿
     * @return array 完整的数据， false 保存出错
     */
    public function autoSave($data){
        $post = $data;

        /* 检查文档类型是否符合要求 */
        $res = $this->checkDocumentType( gp('type'), gp('pid') );
        if(!$res['status']){
        	$this->myerror = $res['info'];
        	return false;
        }
        $if_save=FALSE;
        //触发自动保存的字段
        $save_list = array('name','title','description','position','link_id','cover_id','deadline','create_time','content');
        foreach ($save_list as $value){
            if(!empty($post[$value])){
                $if_save = true;
                break;
            }
        }

        if(!$if_save){
            $this->myerror = '您未填写任何内容';
            return false;
        }
        $post['status']=DRAFT_VAL;
        $this->attributes=$post;
        if(!$this->validate()){
            $this->myerror=get_model_ajax_error($this);
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->save(); //添加基础内容
            if(!$id){
    			$this->myerror = '新增基础内容出错！'.get_model_ajax_error($this);
                return false;
            }
            $data['id'] = $this->id;
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
    			$this->myerror = '更新基础内容出错！'.get_model_ajax_error($this);
                return false;
            }
        }

        /* 添加或新增扩展内容 */
        $logic = $this->logic($data['model_id']);
        $id=$this->id;
        if(!$logic->autoSave($id)){
            if(isset($id)){ //新增失败，删除基础数据
                $this->deleteByPk($id);
            }
            $this->myerror = $logic->getMyError();
            return false;
        }

        //内容添加或更新完成
        return $data;
    }
    
    
    /**
     * 获取详情页数据
     * @param  integer $id 文档ID
     * @return array       详细数据
     */
    public function detail($id){
        /* 获取基础数据 */
        $info = $this->findByPk($id);
        $info=findall_to_array($info);
        if(!(is_array($info) || 1 !== $info['status'])){
            $this->myerror = '文档被禁用或已删除！';
            return false;
        }

        /* 获取模型数据 */
        $logic  = $this->logic($info['model_id']);
        $detail = $logic->detail($id); //获取指定ID的数据
        $detail=findall_to_array($detail);
        if(!$detail){
            $this->myerror = $logic->getMyError();
            return false;
        }
        $info = array_merge($info, $detail);

        return $info;
    }
    
}
