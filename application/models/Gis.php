<?php
class Gis{
	public function getLngLat($address){
		$key = "UL7BZ-KS4RX-Z7A4H-7S7S2-X6AH3-PZB64";
		$url = "http://apis.map.qq.com/ws/geocoder/v1/?address=$address&key=$key";
		$result = getData($url);

		$obj = json_decode($result);

		if(!empty($obj->result->title) 
			&& !empty($obj->result->location->lng)
			&& !empty($obj->result->location->lat)){
			$pos = new stdClass();
			$pos->title = $obj->result->title;
			$pos->lng = $obj->result->location->lng;
			$pos->lat = $obj->result->location->lat;
			return $pos;
		}else{
			return false;
		}
	}
}
/**
* get数据
*/
function getData($url){
	$ch = curl_init();//初始化curl
	curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
	curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时
	curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
	$output = curl_exec($ch);//运行curl
	curl_close($ch);
	return $output;
}
?>