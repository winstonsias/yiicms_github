<?php

/**
 * This is the model class for table "{{channel}}".
 *
 * The followings are the available columns in table '{{channel}}':
 * @property string $id
 * @property string $pid
 * @property string $title
 * @property string $url
 * @property string $sort
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property integer $target
 */
class Channel extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{channel}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, url', 'required'),
			array('status, target', 'numerical', 'integerOnly'=>true),
			array('pid, sort, create_time, update_time', 'length', 'max'=>10),
			array('title', 'length', 'max'=>30),
			array('url', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, title, url, sort, create_time, update_time, status, target', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'title' => 'Title',
			'url' => 'Url',
			'sort' => 'Sort',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
			'status' => 'Status',
			'target' => 'Target',
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
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('target',$this->target);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Channel the static model class
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
}
