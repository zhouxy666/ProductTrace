<?php

/**
 * Class Echarts
 */
class Echarts extends CI_Model{

	protected $data;
	protected $option;
	public function __construct()
	{
		parent::__construct();
	}

	public function setData($data){
		$this->data = $data;
	}



	//处理Option数组
	public function prepare()
	{
		//从$option读取xAxis部分 并转为对象
		if(!empty($this->option["xAxis"])){
			$result = [];
			$obj = new stdClass();
			foreach($this->option["xAxis"] as $k=>$v){
				$obj->$k = $v;
			}
			$result[] = $obj;
			$this->option["xAxis"] = $result;
		}
		//从$option读取yAxis部分 并转为对象
		if(!empty($this->option["yAxis"])){
			$result = [];
			$obj = new stdClass();
			foreach($this->option["yAxis"] as $k=>$v){
				$obj->$k = $v;
			}
			$result[] = $obj;
			$this->option["yAxis"] = $result;
		}
	}

	/**
	 * 解析xAxis
	 * @return string
	 */
	/*
	public function parseXAxis(){

		if(empty($this->option["xAxis"])){
			return "";
		}
		//从选项文件读取xAxis部分 并转为对象
		$result = [];
		foreach($this->option["xAxis"] as $k=>$v){
			$xAxis = new stdClass();
			$xAxis->$k = $v;
			$result[] = $xAxis;
		}

		$str = "[";
		foreach($result as $row){
			if(is_object($row)){
				$str .= $this->ObjToStr($row);
			}
		}
		$str .="]";
		return $str;
	}
	*/

	public function setOption(){

		$xAxis = new stdClass();
		$xAxis->type = "category";
		$xAxis->data = ["A","B","C","D"];

		$yAxis = new stdClass();
		$yAxis->type = "value";

		$title = new stdClass();
		$title->text = "测试标题";
		$title->subtext = "";

		$tooltip = new stdClass();
		$tooltip->trigger = "axis";

		$legend = new stdClass();
		$legend->data = ['食品生产企业','食品销售企业','餐饮服务企业'];


		$series = new stdClass();
		$series->name = "系列1";
		$series->type = "bar";
		$series->data = [0,0,0,0];
		$series->itemStyle = "{normal: {borderRadius: 5,color : '#5ab1ef',label : {show : true,textStyle : {fontSize : '20',fontFamily : '微软雅黑',fontWeight : 'bold'}}}}";

		$this->option = [
			"title"=>$title,
			"tooltip"=>$tooltip,
			"legend"=>$legend,
			"calculable"=>true,
			"xAxis"=>[$xAxis],
			"yAxis"=>[$yAxis],
			"series"=>[$series,$series,$series],
//			[
//				"name"=>"",
//				"type"=>"bar",
//				//"radius"=>"55%",
//				//"center"=>"['50%','60%']",
//				"data"=>"[]",
//				"itemStyle"=>"{normal: {borderRadius: 5,color : \"#5ab1ef\",label : {show : true,textStyle : {fontSize : '20',fontFamily : '微软雅黑',fontWeight : 'bold'}}}}",
//			]
		];
	}

	public function setTestData(){
		$this->data = [
			"title"=>[
				"text" => "某站点用户访问来源",
				'subtext'=> "",
			],
			"series"=>[
				[
					"name"=>"食品生产企业",
					"data"=>[
						["name"=>"A", "value"=>0],
						["name"=>"B", "value"=>0],
						["name"=>"C", "value"=>0],
						["name"=>"D", "value"=>0],
					],
				],
				[
					"name"=>"食品销售企业",
					"data"=>[
						["name"=>"A", "value"=>0],
						["name"=>"B", "value"=>2],
						["name"=>"C", "value"=>0],
						["name"=>"D", "value"=>0],
					]
				],
				[
					"name"=>"餐饮服务企业",
					"data"=>[
						["name"=>"A", "value"=>0],
						["name"=>"B", "value"=>0],
						["name"=>"C", "value"=>0],
						["name"=>"D", "value"=>0],
					]
				],
			]
		];
	}

	/**
	 * 解析Options参数中的每一项Option
	 * @param string $attrName 属性名称
	 * @param object $attrVal 属性值
	 * @return string
	 */
	public function resolveOption($attrName,$attrVal){
		//处理bool型 属性值
		if(is_bool($attrVal)){
			if($attrVal){
				return "$attrName : true";
			}else{
				return "$attrName : false";
			}
		}
		//处理string型 属性值
		if(is_string($attrVal)){
			return "$attrName : '$attrVal'";
		}
		//处理object型 属性值
		if(is_object($attrVal)){
			return "$attrName : ".$this->ObjToStr($attrVal);
		}
		//处理array型 属性值
		if(is_array($attrVal)){
			return "$attrName : ".$this->ArrToStr($attrName,$attrVal);
		}
	}
	/**
	 * php 对象转字符串
	 * @param object $obj
	 * @return string
	 */
	public function ObjToStr($obj){
		$result = get_object_vars($obj);//将对象转为数组
		$attr = [];	//对象属性数组
		foreach($result as $attrKey=>$attrVal){
			if(is_string($attrVal)){
				$attr[] = "$attrKey:'$attrVal'";
			}
			if(is_bool($attrVal)){
				if($attrVal==true){
					$attr[] = "$attrKey:true";
				}else{
					$attr[] = "$attrKey:false";
				}
			}
			if(is_array($attrVal)){
				$attr[] = "$attrKey:".$this->ArrToStr($attrKey,$attrVal);
			}
		}
		return "{".join(",",$attr)."}";
	}

	/**
	 * php 数组转字符串
	 * @param string $attrName  属性名称
	 * @param string $attrValue 属性值
	 * @return string
	 */
	public function ArrToStr($attrName,$attrValue){
		$data = [];
		foreach ($attrValue as $row){
			//数组成员是字符串
			if(is_string($row)){
				$data[] = "'$row'";
			}
			//数组成员是整数
			if(is_int($row)){
				$data[] = $row;
			}
			//数组成员是对象
			if(is_object($row)){
				$data[]= $this->ObjToStr($row);
			}
			//数组成员是数组
			if(is_array($row)){
				$data[] = join(",",$row);
			}
		}
		return "[".join(",",$data)."]";
	}

	/*
	public function getLegend(){
		$data = [];
		foreach($this->data["series"] as $row){
			$data [] = "'".$row["name"]."'";
		}
		return $data;
	}
	public function getXAxis(){
		if(count($this->data["series"])>1){
			$series = $this->data["series"][0];
			if(!empty($series["data"])){
				$data = [];
				foreach($series["data"] as $row){
					$data [] = "'".$row["name"]."'";
				}
				return $data;
			}
			return [];
		}
		return [];
	}
	*/

	public function show(){
		// 处理坐标轴选项
		//$this->prepare();
		//print_r($this->option);die;
		// 合并标题数据
		//$this->option["title"] = array_merge($this->option["title"],$this->data["title"]);

		/*
		$result = [];
		foreach($this->data["series"] as $row) {
			$tmp = "{\n";
			//优先使用data["series"][$k] 其实使用option["series"][$k]
			//从模板$option中继承所有属性 合并$row产生新数据 多态? 模板+数据？
			$newRow = array_merge($this->option["series"],$row);

			foreach ($newRow as $key=>$value) {
				if (in_array($key, ["name","type"])) {
					$tmp .= "\t$key: '$value',\n";
				}
				if (in_array($key, ["itemStyle"])) {
					$tmp .= "\t$key: $value,\n";
				}
				if (in_array($key, ["data"])) {
					if(is_array($value)){
						$arrData = [];

						foreach($value as $row){
							$arrData[] = $row["value"];
						}
						$strData = "[".join(",",$arrData)."]";
					}
					$tmp .= "\t$key: $strData,\n";
				}
			}
			$tmp .= "}";
			$result[] = $tmp;
		}

		//$this->option["xAxis"] = $this->parseXAxis();
		$this->option["series"] = "[".join(",",$result)."]";
		*/

		$tmp = [];
		foreach ($this->option as $attrName=>$attrVal){
			$tmp[] = $this->resolveOption($attrName,$attrVal);
		}
		$str = "option = {\n".join(",\n",$tmp)."\n}";
		return $str;
	}
}