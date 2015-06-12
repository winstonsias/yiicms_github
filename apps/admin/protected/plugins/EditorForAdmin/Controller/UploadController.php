<?php

class UploadController extends BackendBaseController{

	public $uploader = null;

	/* 上传图片 */
	public function upload(){
		setSession('upload_error', null);
		/* 上传配置 */
		$setting = C('EDITOR_UPLOAD');

		/* 调用文件上传组件上传文件 */
		Yii::import('ext.Upload.Upload');
		$this->uploader = new Upload($setting, 'Local');
		$info   = $this->uploader->upload($_FILES);
		if($info){
			$url = $setting['rootPath'].$info['imgFile']['savepath'].$info['imgFile']['savename'];
			$url = str_replace('./', '/', $url);
			$info['fullpath'] = getUriTrimIndex().$url;
		}
		setSession('upload_error', $this->uploader->getError());
		return $info;
	}

	//keditor编辑器上传图片处理
	public function actionKe_upimg(){
		/* 返回标准数据 */
		$return  = array('error' => 0, 'info' => '上传成功', 'data' => '');
		$img = $this->upload();
		/* 记录附件信息 */
		if($img){
			$return['url'] = $img['fullpath'];
			unset($return['info'], $return['data']);
		} else {
			$return['error'] = 1;
			$return['message']   = getSession('upload_error');
		}

		/* 返回JSON数据 */
		exit(json_encode($return));
	}

	//ueditor编辑器上传图片处理
	public function actionUe_upimg(){

		$img = $this->upload();
		$return = array();
		$return['url'] = $img['fullpath'];
		$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
		$return['title'] = $title;
		$return['original'] = $img['imgFile']['name'];
		$return['state'] = ($img)? 'SUCCESS' : getSession('upload_error');
		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}

}
