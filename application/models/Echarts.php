<?php

/**
 * Class Echarts
 */
class Echarts extends CI_Model
{

	protected $titleText;    	//Echarts 主标题
	protected $legendData;    	//Echarts 标记数据
	protected $xAxisData;		//Echarts x轴数据
	protected $data;        	//Echarts 显示数据
	protected $option;			//Echarts Option
	protected $cacheTime;     	//缓存时间 单位秒

	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}

	/**
	 * 解析Options参数中的每一项Option
	 * @param string $attrName 属性名称
	 * @param object $attrVal 属性值
	 * @return string
	 */
	public function resolveOption($attrName, $attrVal)
	{
		//处理bool型 属性值
		if (is_bool($attrVal)) {
			if ($attrVal) {
				return "$attrName : true";
			} else {
				return "$attrName : false";
			}
		}
		//处理string型 属性值
		if (is_string($attrVal)) {
			return "$attrName : '$attrVal'";
		}
		//处理object型 属性值
		if (is_object($attrVal)) {
			return "$attrName : " . $this->ObjToStr($attrVal);
		}
		//处理array型 属性值
		if (is_array($attrVal)) {
			return "$attrName : " . $this->ArrToStr($attrVal);
		}
	}

	/**
	 * php 对象转字符串
	 * @param object $obj
	 * @return string
	 */
	public function ObjToStr($obj)
	{
		$result = get_object_vars($obj);//将对象转为数组
		$attr = [];    //对象属性数组
		foreach ($result as $attrKey => $attrVal) {
			if (is_string($attrVal)) {
				$attr[] = "$attrKey:'$attrVal'";
			}
			if (is_int($attrVal)) {
				$attr[] = "$attrKey:$attrVal";
			}
			if (is_bool($attrVal)) {
				if ($attrVal == true) {
					$attr[] = "$attrKey:true";
				} else {
					$attr[] = "$attrKey:false";
				}
			}
			if (is_object($attrVal)) {
				$attr[] = "$attrKey:" . $this->ObjToStr($attrVal);
			}
			if (is_array($attrVal)) {
				$attr[] = "$attrKey:" . $this->ArrToStr($attrVal);
			}
		}
		return "{" . join(",", $attr) . "}";
	}

	/**
	 * php 数组转字符串
	 * @param string $attrValue 属性值
	 * @return string
	 */
	public function ArrToStr($attrValue)
	{
		$data = [];
		foreach ($attrValue as $row) {
			//数组成员是字符串
			if (is_string($row)) {
				$data[] = "'$row'";
			}
			//数组成员是整数
			if (is_int($row)) {
				$data[] = $row;
			}
			//数组成员是对象
			if (is_object($row)) {
				$data[] = $this->ObjToStr($row);
			}
			//数组成员是数组
			if (is_array($row)) {
				$data[] = $this->ArrToStr($row);
			}
		}
		return "[" . join(",", $data) . "]";
	}

	/**
	 * 设置缓存时间 默认缓存300秒
	 * @param int $cacheTime
	 * @return $this
	 */
	public function setCache($cacheTime = 300)
	{
		$this->cacheTime = $cacheTime;
		return $this;
	}

	/**
	 * 设置Echarts 标题
	 * @param $val
	 * @return $this
	 */
	public function setTitleText($val)
	{
		$this->titleText = $val;
		return $this;
	}

	/**
	 * 设置Echarts 标记数据
	 * @param $val
	 * @return $this
	 */
	public function setLegendData($val)
	{
		$this->legendData = $val;
		return $this;
	}
	/**
	 * 设置Echarts x轴数据
	 * @param $val
	 * @return $this
	 */
	public function setXAxisData($val){
		$this->xAxisData = $val;
		return $this;
	}
	/**
	 * 设置Echarts 数据
	 * @param $data
	 * @return $this
	 */
	public function setSeries($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * 构建多分组柱状图 Option参数
	 * @return $this
	 */
	public function buildMbarOption(){
		//构建标题
		$title = new stdClass();
		$title->text = $this->titleText;
		$title->subtext = "";

		//构建Legend对象 标记分组
		$legend = new stdClass();
		$legend->data = $this->legendData;

		//构建x轴 value数值轴 category类目轴 time时间轴 log对数轴
		$xAxis = new stdClass();
		$xAxis->type = "category";
		$xAxis->data = $this->xAxisData;

		//构建y轴 value数值轴 category类目轴 time时间轴 log对数轴
		$yAxis = new stdClass();
		$yAxis->type = "value";

		//构建提示框组件
		//item 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		//axis 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
		$tooltip = new stdClass();
		$tooltip->trigger = "axis";


		//构建系列列表
		$series = [];
		$i=0;
		foreach ($this->data as $row) {
			$i++;
			$itemStyle = new stdClass();
			$itemStyle->normal = new stdClass();
			$itemStyle->normal->borderRadius = 5;
			$itemStyle->normal->color = "#5ab1ef";
			$itemStyle->normal->label = new stdClass();
			$itemStyle->normal->label->show = true;
			$itemStyle->normal->label->fontSize = "20";
			$itemStyle->normal->label->fontFamily = "微软雅黑";
			$itemStyle->normal->label->fontWeight = "bold";
			if($i==1){
				$itemStyle->normal->color="#2ec7c9";
			}
			if($i==2){
				$itemStyle->normal->color="#d87a80";
			}
			if($i==3){
				$itemStyle->normal->color="#5ab1ef";
			}

			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = "bar";
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}

		$this->option = [
			"title" => $title,
			"tooltip" => $tooltip,
			"legend" => $legend,
			"calculable" => true,
			"xAxis" => [$xAxis],
			"yAxis" => [$yAxis],
			"series" => $series,
		];

		return $this;
	}

	/**
	 * 构建柱状图 Option参数
	 * @return $this
	 */
	public function buildBarOption(){
		//构建标题
		$title = new stdClass();
		$title->text = $this->titleText;
		$title->x = "center";

		//构建x轴 value数值轴 category类目轴 time时间轴 log对数轴
		$xAxis = new stdClass();
		$xAxis->type = "category";
		$xAxis->show = true;
		$xAxis->axisLabel = new stdClass();
		$xAxis->axisLabel->interval = "auto";
		$xAxis->axisLabel->rotate = 60;
		$xAxis->data = $this->xAxisData;

		//构建y轴 value数值轴 category类目轴 time时间轴 log对数轴
		$yAxis = new stdClass();
		$yAxis->type = "value";

		//构建提示框组件
		//item 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		//axis 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
		$tooltip = new stdClass();
		$tooltip->trigger = "item";
		$tooltip->formatter = "{a} <br/>{b} : {c}%";

		/*
		$dataZoom = new stdClass();
		$dataZoom->show = true;
		$dataZoom->realtime = true;
		$dataZoom->start = 30;
		$dataZoom->end = 100;
		*/

		//构建系列列表
		$series = [];
		$itemStyle = new stdClass();
		$itemStyle->normal = new stdClass();
		$itemStyle->normal->label = new stdClass();
		$itemStyle->normal->label->show = true;
		$itemStyle->normal->label->formatter = "{c}";

		foreach ($this->data as $row) {
			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = "bar";
			$tmp->radius = "55%";
			$tmp->center = ["50%","60%"];
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}

		$this->option = [
			"title" => $title,
			"tooltip" => $tooltip,
			"calculable" => true,
			"xAxis" => [$xAxis],
			"yAxis" => [$yAxis],
			//"dataZoom" => [$dataZoom],
			"series" => $series,
		];

		return $this;
	}

	/**
	 * 构建线状图 Option参数
	 * @return $this
	 */
	public function buildLineOption(){
		//构建标题
		$title = new stdClass();
		$title->text = $this->titleText;

		//构建x轴 value数值轴 category类目轴 time时间轴 log对数轴
		$xAxis = new stdClass();
		$xAxis->type = "category";
		$xAxis->boundaryGap = false;
		$xAxis->axisLabel = new stdClass();
		$xAxis->axisLabel->interval = 0;
		$xAxis->axisLabel->rotate = 60;
		$xAxis->data = $this->xAxisData;

		//构建y轴 value数值轴 category类目轴 time时间轴 log对数轴
		$yAxis = new stdClass();
		$yAxis->type = "value";

		//构建提示框组件
		//item 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		//axis 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
		$tooltip = new stdClass();
		$tooltip->trigger = "axis";


		//构建系列列表
		$series = [];
		$itemStyle = new stdClass();
		$itemStyle->normal = new stdClass();
		$itemStyle->normal->areaStyle = new stdClass();
		$itemStyle->normal->areaStyle->type = "default";

		foreach ($this->data as $row) {
			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = "line";
			$tmp->smooth = true;
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}

		$this->option = [
			"title" => $title,
			"tooltip" => $tooltip,
			"calculable" => true,
			"xAxis" => [$xAxis],
			"yAxis" => [$yAxis],
			"series" => $series,
		];

		return $this;
	}

	/**
	 * 构建饼状图 Option参数
	 * @return $this
	 */
	public function buildPieOption(){
		//构建标题
		$title = new stdClass();
		$title->text = $this->titleText;
		$title->x = "center";

		//构建x轴 value数值轴 category类目轴 time时间轴 log对数轴
		$xAxis = new stdClass();
		$xAxis->type = "category";
		$xAxis->boundaryGap = false;
		$xAxis->axisLabel = new stdClass();
		$xAxis->axisLabel->interval = 0;
		$xAxis->axisLabel->rotate = 60;
		$xAxis->data = $this->xAxisData;

		//构建y轴 value数值轴 category类目轴 time时间轴 log对数轴
		$yAxis = new stdClass();
		$yAxis->type = "value";

		//构建提示框组件
		//item 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		//axis 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
		$tooltip = new stdClass();
		$tooltip->trigger = "item";
		$tooltip->formatter = "{a} <br/>{b} : {c} ({d}%)";

		//构建Legend对象 标记分组
		$legend = new stdClass();
		$legend->orient = "vertical";
		$legend->x = "left";
		$legend->data = $this->legendData;

		//构建系列列表
		$series = [];
		$itemStyle = new stdClass();
		$itemStyle->normal = new stdClass();
		$itemStyle->normal->label = new stdClass();
		$itemStyle->normal->label->show = true;
		$itemStyle->normal->label->formatter = "{b} - {c} ({d}%)";

		foreach ($this->data as $row) {
			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = "pie";
			$tmp->radius = "55%";
			$tmp->center = ["50%","60%"];
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}

		$this->option = [
			"title" => $title,
			"tooltip" => $tooltip,
			"legend" => $legend,
			"calculable" => true,
			"series" => $series,
		];

		return $this;
	}


	/**
	 * 构建地图 Option参数
	 * @return $this
	 */
	public function buildMapOption(){
		//构建标题
		$title = new stdClass();
		$title->text = $this->titleText;
		$title->x = "center";

		//构建提示框组件
		//item 数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		//axis 坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。
		$tooltip = new stdClass();
		$tooltip->trigger = "item";

		$dataRange = new stdClass();
		$dataRange->min = 0;
		$dataRange->max = 100;
		$dataRange->color = ["orange","yellow"];
		$dataRange->text = ["高","低"];
		$dataRange->calculable = true;


		//构建系列列表
		$series = [];
		$itemStyle = new stdClass();
		$itemStyle->normal = new stdClass();
		$itemStyle->normal->label = new stdClass();
		$itemStyle->normal->label->show = true;
		$itemStyle->emphasis = new stdClass();
		$itemStyle->emphasis->label = new stdClass();
		$itemStyle->emphasis->label->show = true;

		foreach ($this->data as $row) {
			$tmp = new stdClass();
			$tmp->name = $row["name"];
			$tmp->type = "map";
			$tmp->mapType = "yunchengshi";
			$tmp->roam = false;
			$tmp->data = $row["data"];
			$tmp->itemStyle = $itemStyle;
			$series[] = $tmp;
		}

		$this->option = [
			"title" => $title,
			"tooltip" => $tooltip,
			"dataRange" => $dataRange,
			"series" => $series,
		];

		return $this;
	}

	/**
	 * 将result数据转为报表数据
	 * @param array $result SQL查询结构
	 * @param string $xAxisField x轴对应的字段名称
	 * @param string $yAxisField y轴对应的字段名称
	 * @param string $name 数据集名称
	 * @return mixed
	 */
	public function adaptResult($result,$xAxisField,$yAxisField,$name){
		$xAxisData = [];
		$seriesData= [];
		foreach($result as $row){
			$xAxisData[] = $row[$xAxisField];
			$tmp = new stdClass();
			$tmp->name 	= $row[$xAxisField];
			$tmp->value	= $row[$yAxisField];
			$seriesData[] = $tmp;
		}
		$series["name"]	= $name;
		$series["data"]	= $seriesData;
		$data["xAxis"]	= $xAxisData;
		$data["series"]	= [$series];
		$data["legend"] = $xAxisData;
		return $data;
	}
	/**
	 * 显示Echarts 图表
	 * @param string $type   图标类型
	 * @param string $title  标题
	 * @param string $result 数据
	 * @param string $id
	 * @return string
	 */
	public function show($type,$title,$result,$id)
	{
		$cacheFile = "chart_$id";
		if (!$str = $this->cache->get($cacheFile)) {

			if($type=="Mbar"){
				$this->Echarts->setTitleText($title);
				$this->Echarts->setLegendData($result["legend"]);
				$this->Echarts->setXAxisData($result["xAxis"]);
				$this->Echarts->setSeries($result["series"]);
				$this->BuildMbarOption();
			}
			if($type=="bar"){
				$this->Echarts->setTitleText($title);
				$this->Echarts->setXAxisData($result["xAxis"]);
				$this->Echarts->setSeries($result["series"]);
				$this->BuildBarOption();
			}
			if($type=="line"){
				$this->Echarts->setTitleText($title);
				$this->Echarts->setXAxisData($result["xAxis"]);
				$this->Echarts->setSeries($result["series"]);
				$this->BuildLineOption();
			}
			if($type=="pie"){
				$this->Echarts->setTitleText($title);
				$this->Echarts->setLegendData($result["legend"]);
				$this->Echarts->setSeries($result["series"]);
				$this->BuildPieOption();
			}
			if($type=="map"){
				$this->Echarts->setTitleText($title);
				$this->Echarts->setSeries($result["series"]);
				$this->BuildMapOption();
			}
			if($type=="json"){
				$callback   = $this->security->xss_clean($this->input->get_post('callback'));
				if(empty($callback)){
					return json_encode($result);
				}else{
					return $callback."(".json_encode($result).")";
				}
			}
			//Option参数转str
			$tmp = [];
			foreach ($this->option as $attrName => $attrVal) {
				$tmp[] = $this->resolveOption($attrName, $attrVal);
			}

			if($type=="map"){
				$str = "(function(id){echarts.util.mapData.params.params.yunchengshi = {getGeoJson: function (callback) {\$.getJSON('/assets/hplus/js/plugins/echarts/geoJson/140800.json', callback);}}
				var myChart = echarts.init(document.getElementById(id));\n";
			}else{
				$str = "(function(id){var myChart = echarts.init(document.getElementById(id));\n";
			}

			$str .= "option = {\n" . join(",\n", $tmp) . "\n}\n";
			$str .= "myChart.setOption(option);})(\"$id\");";

			// Save into the cache for 5 minutes
			$this->cache->save($cacheFile, $str, $this->cacheTime);
		}
		return $str;
	}
}