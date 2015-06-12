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
**********Config.php**********
**********USE:**********
**********AUTHOR:WINSTON**********
**********DATE:2014-10-28**********
*/
class Config extends LjhModel
{
    public function tableName()
    {
        return "{{config}}";
    }
/**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('remark, value', 'required'),
            array('type, group, status, sort', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>30),
            array('title', 'length', 'max'=>50),
            array('extra', 'length', 'max'=>255),
            array('remark', 'length', 'max'=>100),
            array('create_time, update_time', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, type, title, group, extra, remark, create_time, update_time, status, value, sort', 'safe', 'on'=>'search'),
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
            'type' => 'Type',
            'title' => 'Title',
            'group' => 'Group',
            'extra' => 'Extra',
            'remark' => 'Remark',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'status' => 'Status',
            'value' => 'Value',
            'sort' => 'Sort',
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
        $criteria->compare('type',$this->type);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('group',$this->group);
        $criteria->compare('extra',$this->extra,true);
        $criteria->compare('remark',$this->remark,true);
        $criteria->compare('create_time',$this->create_time,true);
        $criteria->compare('update_time',$this->update_time,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('value',$this->value,true);
        $criteria->compare('sort',$this->sort);

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
    	    $this->create_time=NOW_TIME;
    	    $this->update_time=NOW_TIME;
    	    $this->status=$this->status?$this->status:1;
	    }else
	    {
	        $this->update_time=NOW_TIME;
	    }
	}
	/**
     * 获取配置列表
     * @return array 配置数组
     */
    public function lists(){
        $criteria=new CDbCriteria();
        $criteria->condition='status=1';
        $criteria->select='type,name,value';
        $data   = $this->findAll($criteria);
        
        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }
	/**
     * 根据配置类型解析配置
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     */
    private function parse($type, $value){
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if(strpos($value,':')){
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                }else{
                    $value =    $array;
                }
                break;
        }
        return $value;
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}