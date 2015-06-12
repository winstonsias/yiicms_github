<?php

class ErrorModule extends CWebModule
{
		
	public $baseUrl = '/error';	
	public $appLayout;

	public function init()
	{
	    $this->appLayout= CMS_FOLDER_NAME.'.modules.error.views.layouts.main';
		// this method is called when the module is being created
		// you may place code here to customize the module or the application		

	}
	
}

