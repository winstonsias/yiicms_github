<?php


	class Editor extends BasePlugin{

		public $info = array(
				'name'=>'Editor',
				'title'=>'前台编辑器',
				'description'=>'用于增强整站长文本的输入和显示',
				'status'=>1,
				'author'=>'thinkphp',
				'version'=>'0.1'
			);

		public function install(){
			return true;
		}

		public function uninstall(){
			return true;
		}

		/**
		 * 编辑器挂载的文章内容钩子
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		public function documentEditFormContent($data){
			$this->render('content',array('addons_data'=>$data,'addons_config'=>$this->getConfig()));
		}

		/**
		 * 讨论提交的钩子使用编辑器插件扩展
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		public function topicComment ($data){
			$this->render('content',array('addons_data'=>$data,'addons_config'=>$this->getConfig()));
		}

	}
