<?php
class LjhHelpers {
	/**
	 * Publish Assets with cache support
	 * 
	 */
	public static function publishAsset($path,$hashByName=false,$level=-1) 
	{
		
		$cache_id= YII_DEBUG ? false : 'asset'.$path.'-1';						
		if($cache_id && file_exists($cache_id)){
			$cache=Yii::app()->cache->get($cache_id);
			if($cache===false){

				$cache=Yii::app()->assetManager->publish($path,$hashByName,-1,YII_DEBUG);			
				Yii::app()->cache->set($cache_id,$cache,7200);
			} 

		} else {			
			$cache=Yii::app()->assetManager->publish($path,$hashByName,-1,YII_DEBUG);			
		}
		
		return $cache;				
	}
       
/**
	* Function to Get All Apps Available
	**/
	
	public static function getAllApps($return_path=false){		
		$cache_id='ljhhelpers-apps';
    	$apps=Yii::app()->cache->get($cache_id);		
		if($apps===false){
			$apps=array();
			$folders_app = get_subfolders_name(Yii::getPathOfAlias('common').DIRECTORY_SEPARATOR.'..') ;    
	        foreach($folders_app as $folder){	 	        	
	        	if(file_exists(Yii::getPathOfAlias('common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.'protected'
	        		.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'environment.php')){
	        		if(!$return_path) $apps[]=$folder; 
	        		else
	        			$apps[]=realpath(Yii::getPathOfAlias('common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$folder); 
	        	}
	        		         	        	
	        }  	        
			Yii::app()->cache->set($cache_id,$apps,7200);
		}
		return $apps;
	}
}
	