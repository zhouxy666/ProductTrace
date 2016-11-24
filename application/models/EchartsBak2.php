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

	/**
	 * 构建标题
	 * @param string $text      主标题
	 * @param string $subText   子标题
	 * @return stdClass
	 */
	public function buildTitle($text="",$subText = ""){
		$title = new stdClass();
		$title->text = $text;
		$title->subtext = $subText;
		return $title;
	}

	/**
	 * 构建x轴
	 * @param string $type x轴类型 value数值轴 category类目轴 time时间轴 log对数轴
	 * @param array $data  x轴数据
	 * @return stdClass
	 */
	public function buildXAxis($type="category",$data){
		$xAxis = new stdClass();
		$xAxis->type = $type;
		$xAxis->data = $data;
		return $xAxis;
	}

	/**
	 * 构建y轴
	 * @param string $type y轴类型 value数值轴 category类目轴 time时间轴 log对数轴
	 * @return stdClass
	 */
	public function buildYAxis($type){
		$yAxis = new stdClass();
		$yAxis->type = $type;
		return $yAxis;
	}

	/**
	 * 构建Legend对象 标记分组
	 * @param $data
	 * @return stdClass
	 */
	public function buildLegend($data){
		$legend = new stdClass();
		$legend->data = $data;
		return $legend;
	}

	/**
	 * 构建提示框组件
	 *
	 * 'item' 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
	 * 'axis' 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
	 * @param string $trigger 触发类型 'item' 'axis'
	 * @return stdClass
	 */
	public function buildToolTip($trigger){
		$tooltip = new stdClass();
		$tooltip->trigger = $trigger;
		return $tooltip;
	}

	/**
	 * 构建系列列表
	 * @param $data
	 * @param $type
	 * @param $itemStyle
	 * @return array|stdClass
	 */
	public function buildSeries($data,$type,$itemStyle){
		$series = [];
		foreach($data as $row){
			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = $type;
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}
		return $series;
	}

	/**
	 * 构建Option参数
	 * @param string $title 标题
	 * @param string $legend 分组
	 * @param string $xAxis x轴
	 * @param array $data 数据
	 * @return $this
	 */
	public function buildMbar($title,$legend,$xAxis,$data){
		$title = $this->buildTitle($title);
		$legend = $this->buildLegend($legend);
		$xAxis = $this->buildXAxis("category",$xAxis);
		$yAxis = $this->buildYAxis("value");
		$tooltip = $this->buildToolTip("axis");
		$itemStyle = new stdClass();
		$itemStyle->normal = new stdClass();
		$itemStyle->normal->borderRadius = 5;
		$itemStyle->normal->color = "#5ab1ef";
		$itemStyle->normal->label = new stdClass();
		$itemStyle->normal->label->show = true;
		$itemStyle->normal->label->fontSize = "20";
		$itemStyle->normal->label->fontFamily = "微软雅黑";
		$itemStyle->normal->label->fontWeight = "bold";
		$series = $this->buildSeries($data,"bar",$itemStyle);

		$this->option = [
			"title"=>$title,
			"tooltip"=>$tooltip,
			"legend"=>$legend,
			"calculable"=>true,
			"xAxis"=>[$xAxis],
			"yAxis"=>[$yAxis],
			"series"=>$series,
		];
		return $this;
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
			return "$attrName : ".$this->ArrToStr($attrVal);
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
			if(is_object($attrVal)){
				$attr[] = "$attrKey:".$this->ObjToStr($attrVal);
			}
			if(is_array($attrVal)){
				$attr[] = "$attrKey:".$this->ArrToStr($attrVal);
			}
		}
		return "{".join(",",$attr)."}";
	}

	/**
	 * php 数组转字符串
	 * @param string $attrValue 属性值
	 * @return string
	 */
	public function ArrToStr($attrValue){
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
				$data[] = $this->ArrToStr($row);
			}
		}
		return "[".join(",",$data)."]";
	}


	public function show($id){
		$tmp = [];
		foreach ($this->option as $attrName=>$attrVal){
			$tmp[] = $this->resolveOption($attrName,$attrVal);
		}
		$str = "(function(id){var myChart = echarts.init(document.getElementById(id));\n";
		$str .= "option = {\n".join(",\n",$tmp)."\n}\n";
		$str .="myChart.setOption(option);})(\"$id\");";
		return $str;
	}
}