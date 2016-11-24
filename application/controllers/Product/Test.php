<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model("Gis");
		$this->load->model("Region");
		$this->load->model("Ent");
		$this->load->model("Batch");
		$this->load->model("ReportRepository");
		$this->load->model("PriceRepository");
		$this->load->model("Echarts");
	}

	public function index()
	{
		$data = [
			"title"=>"某站点用户访问来源",
			"legend"=>['食品生产企业','食品销售企业','餐饮服务企业'],
			"xAxis"=> ["A","B","C","D"],
			"series"=>[
				[
					"name"=>"食品生产企业",
					"data"=>[0,0,0,0],
				],
				[
					"name"=>"食品销售企业",
					"data"=>[0,0,0,0],
				],
				[
					"name"=>"餐饮服务企业",
					"data"=>[0,0,0,0]
				],
			]
		];
		echo $this->Echarts->buildMbar($data["title"],$data["legend"],$data["xAxis"],$data["series"])->show("chart2");
		die;
		$option = [
			"title"=>[
				"text"=>"",
				"subtext"=>"",
				"x"=>"center",
			],
			"tooltip"=>[
				"trigger"=>"item",
				"formatter"=>"{a} <br/>{b} : {c} ({d}%)",
			],
			"legend"=>[
				"orient"=>"vertical",
				"left"=>"left",
				"data"=>"[]",
			],
			"calculable"=>"true",
			"series"=>[
				"name"=>"",
				"type"=>"bar",
				//"radius"=>"55%",
				//"center"=>"['50%','60%']",
				"data"=>"[]",
				"itemStyle"=>"{normal: {borderRadius: 5,color : \"#5ab1ef\",label : {show : true,textStyle : {fontSize : '20',fontFamily : '微软雅黑',fontWeight : 'bold'}}}}",
			]
		];

		$option["title"] = array_merge($option["title"],$data["title"]);


		$result = [];
		foreach($data["series"] as $row) {
			$tmp = "{\n";
			//优先使用data["series"][$k] 其实使用option["series"][$k]
			//从模板$option中继承所有属性 合并$row产生新数据 多态? 模板+数据？
			$newRow = array_merge($option["series"],$row);

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

		$option["series"] = "[".join(",",$result)."]";

		$str = "
		option = {
			title : {
				text: '".$option["title"]["text"]."',
				subtext: '".$option["title"]["subtext"]."',
				x:'".$option["title"]["x"]."'
			},
			tooltip : {
				trigger: '".$option["tooltip"]["trigger"]."',
				formatter: '".$option["tooltip"]["formatter"]."'
			},
			legend: {
				orient: '".$option["legend"]["orient"]."',
				left: '".$option["legend"]["left"]."',
				data: ".$option["legend"]["data"]."
			},
			calculable: ".$option["calculable"].",
			series: ".$option["series"];
		echo $str;
		die;





		echo "所有企业总数：";
		echo $this->ReportRepository->getEntCount();
		echo "<br/>";

		echo "所有企业批次总数（批）：";
		echo $this->ReportRepository->getProductBatCount();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";

		echo "福同惠生产批次总数（批）:";
		echo $this->ReportRepository->getProductBatCount(-1);
		echo "<br/>";

		echo "所有企业产品总数（种）：";
		echo $this->ReportRepository->getProductTypeCount();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";

		echo "福同惠生产产品总数（种）:";
		echo $this->ReportRepository->getProductTypeCount(-1);
		echo "<br/>";

		echo "所有企业累计产量（件）：";
		echo $this->ReportRepository->getAccProduction();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";

		echo "福同惠累计产量（件）：";
		echo $this->ReportRepository->getAccProduction(-1);
		echo "<br/>";

		echo "所有企业原料台账：";
		echo $this->ReportRepository->getProductResCount();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";

		echo "福同惠原料台账：";
		echo $this->ReportRepository->getProductResCount(-1);
		echo "<br/>";

		echo "累计产值：";
		echo $this->PriceRepository->getAccPrice();
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";

		echo "福同惠累计产值：";
		echo $this->PriceRepository->getAccPrice(-1);
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		die;
		//print_r($this->region->result());
		//print_r($this->region->row(140802));
		//print_r($this->Ent->result());
		//企业批次数量
		$ent = $this->Ent->getById(11);
		print_r($ent);
		echo $this->Batch->setEnt(-1)->getTotal();
		echo $this->Batch->setEnt(11)->getTotal();

		return;
		$result=$this->Gis->getLngLat("山西省运城市盐湖区寰烁科技");
		if($result){
			echo "地址：".$result[0]."<br/>";
			echo "经度：".$result[1]."<br/>";
			echo "纬度：".$result[2]."<br/>";
		}
	}
}
