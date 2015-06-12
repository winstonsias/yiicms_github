<?php

/**
 * This is the model class for table "{{document_article}}".
 *
 * The followings are the available columns in table '{{document_article}}':
 * @property string $id
 * @property integer $parse
 * @property string $content
 * @property string $template
 * @property string $bookmark
 */
class DocumentArticle extends LjhDocumentModelBase
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{document_article}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('parse', 'numerical', 'integerOnly'=>true),
			array('id, bookmark', 'length', 'max'=>10),
			array('template', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parse, content, template, bookmark', 'safe', 'on'=>'search'),
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
			'parse' => 'Parse',
			'content' => 'Content',
			'template' => 'Template',
			'bookmark' => 'Bookmark',
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
		$criteria->compare('parse',$this->parse);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('bookmark',$this->bookmark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DocumentArticle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * 新增或添加一条文章详情
	 * @param  number $id 文章ID
	 * @return boolean    true-操作成功，false-操作失败
	 */
	public function winston_update($id = 0){
		/* 获取文章数据 */
	    $data=$_POST;
		if($data === false){
			return false;
		}
		/* 添加或更新数据 */
		if(empty($data['id'])){//新增数据
		    $_POST['id']=$id;
		    $this->attributes=$_POST;
			$status = $this->save();
			if(!$status){
				$this->myerror = '新增详细内容失败！';
				return false;
			}
		} else { //更新数据
			$status = $this->updateByPk($id,$data);
			if(false === $status){
				$this->myerror = '更新详细内容失败！';
				return false;
			}
		}

		return true;
	}
	
	
	/**
	 * 保存为草稿
	 * @return true 成功， false 保存出错
	 */
	public function autoSave($id = 0){
		/* 获取文章数据 */
		$data = $_POST;
		if(!$data){
			return false;
		}

		/* 添加或更新数据 */
		if(empty($data['id'])){//新增数据
			$_POST['id']=$id;
		    $this->attributes=$_POST;
			$status = $this->save();
			if(!$status){
				$this->myerror = '新增详细内容失败！'.get_model_ajax_error($this);
				return false;
			}
		} else { //更新数据
			$status = $this->updateByPk($id,$data);
			if(false === $status){
				$this->myerror = '更新详细内容失败！'.get_model_ajax_error($this);
				return false;
			}
		}

		return true;
	}
}
