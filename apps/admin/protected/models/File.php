<?php

/**
 * This is the model class for table "{{file}}".
 *
 * The followings are the available columns in table '{{file}}':
 * @property string $id
 * @property string $name
 * @property string $savename
 * @property string $savepath
 * @property string $ext
 * @property string $mime
 * @property string $size
 * @property string $md5
 * @property string $sha1
 * @property integer $location
 * @property string $create_time
 */
class File extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{file}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'required'),
			array('location', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('savepath','length','max'=>100),
			array('savename', 'length', 'max'=>20),
			array('ext', 'length', 'max'=>5),
			array('mime, sha1', 'length', 'max'=>40),
			array('size, create_time', 'length', 'max'=>10),
			array('md5', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, savename, savepath, ext, mime, size, md5, sha1, location, create_time', 'safe', 'on'=>'search'),
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
			'savename' => 'Savename',
			'savepath' => 'Savepath',
			'ext' => 'Ext',
			'mime' => 'Mime',
			'size' => 'Size',
			'md5' => 'Md5',
			'sha1' => 'Sha1',
			'location' => 'Location',
			'create_time' => 'Create Time',
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
		$criteria->compare('savename',$this->savename,true);
		$criteria->compare('savepath',$this->savepath,true);
		$criteria->compare('ext',$this->ext,true);
		$criteria->compare('mime',$this->mime,true);
		$criteria->compare('size',$this->size,true);
		$criteria->compare('md5',$this->md5,true);
		$criteria->compare('sha1',$this->sha1,true);
		$criteria->compare('location',$this->location);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return File the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
/**
     * 文件上传
     * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        $setting['callback'] = array($this, 'isFile');
		$setting['removeTrash'] = array($this, 'removeTrash');
		Yii::import('ext.Upload.Upload');
        $Upload = new Upload($setting, $driver, $config);
        $info   = $Upload->upload($files);
        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }

                /* 记录文件信息 */
                $value['savepath'] = substr($setting['rootPath'], 1).$value['savepath'].$value['savename'];	//在模板里的url路径
                $value['status']=1;
                $value['create_time']=time();
                $this->attributes=$value;
                if($this->save()){
                    $id=$this->id;
                    $value['id'] = $id;
                } else {
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                    
                    unset($info[$key]);
                }
            }
            return $info; //文件上传成功
        } else {
            $this->myerror =  $Upload->getError();
            return false;
        }
    }
    
  
    
    
    
	/**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            E('缺少参数:md5');
        }
        /* 查找文件 */
		$map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
		$ret=$this->find(array('condition'=>"md5='".$file['md5']."' and sha1='".$file['sha1']."'"));
		$ret=findall_to_array($ret);
		if($ret){$ret['path']=$ret['savepath'];}
        return $ret;
    }
	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->findByPk($data['id'])->delete();
	}

}
