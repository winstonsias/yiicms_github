<?php

/**
 * This is the model class for table "{{hooks}}".
 *
 * The followings are the available columns in table '{{hooks}}':
 * @property string $id
 * @property string $name
 * @property string $description
 * @property integer $type
 * @property string $update_time
 * @property string $addons
 */
class Hooks extends LjhModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{hooks}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>40),
			array('update_time', 'length', 'max'=>10),
			array('addons', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, type, update_time, addons', 'safe', 'on'=>'search'),
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
			'description' => 'Description',
			'type' => 'Type',
			'update_time' => 'Update Time',
			'addons' => 'Addons',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('addons',$this->addons,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hooks the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
/**
     * 更新插件里的所有钩子对应的插件
     */
    public function updateHooks($addons_name){
        $addons_class = get_addon_class($addons_name);//获取插件名
        if(!file_exists(C('PLUGINS_PATH')."/{$addons_name}/{$addons_name}.php"))
        {
           $this->ajaxReturn("插件{$addons_name}文件不存在");
        }
        Yii::import("application.plugins.{$addons_name}.{$addons_name}");
        if(!class_exists($addons_class)){
            $this->myerror = "未实现{$addons_name}插件的入口文件";
            return false;
        }
        $methods = get_class_methods($addons_class);
        $hooks = $this->findAll(
            array(
                'select'=>'id,name',
            )
        );
        $hooks=findall_field_to_array($hooks,'id,name');
        $common = array_intersect($hooks, $methods);

        if(!empty($common)){
            foreach ($common as $hook) {
                $flag = $this->updateAddons($hook, array($addons_name));
                if(false === $flag){
                    $this->removeHooks($addons_name);
                    return false;
                }
            }
        } else {
            $this->myerror = '插件未实现任何钩子';
            return false;
        }
        return true;
    }
    
    
 /**
     * 更新单个钩子处的插件
     */
    public function updateAddons($hook_name, $addons_name){
        $o_addons = $this->find(
            array(
                'condition'=>"name='".$hook_name."'",
                'select'=>'addons'
            )
        );
        $o_addons=$o_addons['addons'];
        if($o_addons)
            $o_addons = str2arr($o_addons);
        if($o_addons){
            $addons = array_merge($o_addons, $addons_name);
            $addons = array_unique($addons);
        }else{
            $addons = $addons_name;
        }

        $flag = $this->updateAll(
            array(
                'addons'=>arr2str($addons)
            ),"name='{$hook_name}'"
        );
        if(false === $flag)
            $this->updateAll(
                array(
                    'addons'=>arr2str($o_addons)
                ),"name='{$hook_name}'"
            );
            
        return $flag;
    }
    
    
/**
     * 去除插件所有钩子里对应的插件数据
     */
    public function removeHooks($addons_name){
        $addons_class = get_addon_class($addons_name);
        if(!file_exists(C('PLUGINS_PATH')."/{$addons_name}/{$addons_name}.php"))
        {
           $this->ajaxReturn("插件{$addons_name}文件不存在");
        }
        Yii::import("application.plugins.{$addons_name}.{$addons_name}");
        if(!class_exists($addons_class)){
            return false;
        }
        $methods = get_class_methods($addons_class);
        $hooks = $this->findAll(
            array(
                'select'=>'id,name',
            )
        );
        $hooks=findall_field_to_array($hooks,'id,name');
        $common = array_intersect($hooks, $methods);
        if($common){
            foreach ($common as $hook) {
                $flag = $this->removeAddons($hook, array($addons_name));
                if(false === $flag){
                    return false;
                }
            }
        }
        return true;
    }
    
    
 /**
     * 去除单个钩子里对应的插件数据
     */
    public function removeAddons($hook_name, $addons_name){
        $o_addons = $this->find(
            array(
                'condition'=>"name='".$hook_name."'",
                'select'=>'addons'
            )
        );
        $o_addons=$o_addons['addons'];
        $o_addons = str2arr($o_addons);
        if($o_addons){
            $addons = array_diff($o_addons, $addons_name);
        }else{
            return true;
        }
         $flag = $this->updateAll(
            array(
                'addons'=>arr2str($addons)
            ),"name='{$hook_name}'"
        );
        if(false === $flag)
            $this->updateAll(
                array(
                    'addons'=>arr2str($o_addons)
                ),"name='{$hook_name}'"
            );
        return $flag;
    }
}
