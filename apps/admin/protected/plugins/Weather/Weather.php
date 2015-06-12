<?php

class Weather extends BasePlugin{

    public $info = array(
        'name' => 'Weather',
        'title' => '天气预报',
        'description' => '天气预报',
        'status' => 1,
        'author' => 'cepljxiongjun',
        'version' => '0.1'
    );
    public function install() {
        return true;
    }
    public function uninstall() {
        return true;
    }
	public function getAddress()
	{
		$config = $this->getConfig();
		$ip = '218.28.0.211';
		$address = json_decode(file_get_contents("http://api.map.baidu.com/location/ip?ak=".$config['ak']."&ip=".$ip."&coor=bd09ll"));
		return $location = (string)$address->content->address_detail->city;
	}
	public function getWeather(){
		$config = $this->getConfig();
		$url = "http://api.map.baidu.com/telematics/v2/weather?location=".$this->getAddress()."&ak=".$config['ak']."";
		$result = file_get_contents($url);
		$content = simplexml_load_string($result);
		$i = 1;
		$list=array();
		foreach($content->results->result as $result){
			if($i> $config['showday'])
				break;
			$list[$i]['date'] = (string)$result->date;
			$list[$i]['weather'] = (string)$result->weather;
			$list[$i]['wind'] = (string)$result->wind;
			$list[$i]['temperature'] = (string)$result->temperature;
			$list[$i]['pictureUrl'] = (string)$result->dayPictureUrl;
			$i++;
		}

		return $list;
		
	}

    //实现的pageTop钩子方法
	public function pageTop(){
		$config = $this->getConfig();
		foreach($config['showplace'] as $k=>$v){
			if ($v == '1'&&$config['display'])
				$this->render('weather',array('width'=>$config['showday']*140,'location'=>$this->getAddress(),
				'lists'=>$this->getWeather(),'addons_config'=>$config));
		}
	}
}

