<?php

/**
 * This is the model class for table "{{document_download}}".
 *
 * The followings are the available columns in table '{{document_download}}':
 * @property string $id
 * @property integer $parse
 * @property string $content
 * @property string $template
 * @property string $file_id
 * @property string $download
 * @property string $size
 */
class DocumentDownload extends LjhDocumentModelBase
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{document_download}}';
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
			array('id, file_id, download', 'length', 'max'=>10),
			array('template', 'length', 'max'=>100),
			array('size', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parse, content, template, file_id, download, size', 'safe', 'on'=>'search'),
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
			'file_id' => 'File',
			'download' => 'Download',
			'size' => 'Size',
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
		$criteria->compare('file_id',$this->file_id,true);
		$criteria->compare('download',$this->download,true);
		$criteria->compare('size',$this->size,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DocumentDownload the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/**
	 * 更新数据
	 * @param intger $id
	 */
	public function winston_update($id = 0){
	    
		/* 获取文章数据 */
	    $data=$_POST;
		if($data === false){
			return false;
		}

		$file = json_decode(think_decrypt(post('file_id')), true);
		if(!empty($file)){
			$data['file_id'] = $file['id'];
			$data['size']    = $file['size'];
		} else {
			$this->myerror = '获取上传文件信息失败！';
			return false;
		}

		/* 添加或更新数据 */
		if(empty($data['id'])){//新增数据
			$data['id']=$id;
		    $this->attributes=$data;
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

		$file = json_decode(think_decrypt(post('file_id')), true);
		if(!empty($file)){
			$data['file_id'] = $file['id'];
			$data['size']    = $file['size'];
		}

		/* 添加或更新数据 */
		if(empty($data['id'])){//新增数据
		    $data['id']=$id;
		    $this->attributes=$data;
			$status = $this->save();
			if(!$status){
				$this->myerror = '新增详细内容失败！'.get_model_ajax_error($this);
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
}
