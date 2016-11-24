<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("ReportRepository");
		$this->load->model("Echarts");
	}

	public function index(){
		$data["productionNum"]	= $this->ReportRepository->countProductNum();		//获得累计产量
		$data["entNum"]			= $this->ReportRepository->countEntNum();			//企业上线总数
		$data["productBatNum"]	= $this->ReportRepository->countBatNum();			//生产批次总数
		$data["productTypeNum"]	= $this->ReportRepository->countProductTypeNum();	//产品类型总数
		$data["productResNum"]	= $this->ReportRepository->countProductResNum();	//原料台账总数

		$data["productBatNumTop"] = $this->ReportRepository->countBatNumGroupByEnt();		//企业批次数量 Top5
		$data["productionNumTop"] = $this->ReportRepository->countProductNumGroupByEnt();	//企业产品数量 Top5
		$this->load->view("product/index/index",$data);
	}

	public function chart(){
		$name 	= $this->security->xss_clean($this->input->get_post('name'));
		$title 	= $this->security->xss_clean($this->input->get_post('title'));
		$type 	= $this->security->xss_clean($this->input->get_post('type'));
		$id    	= $this->security->xss_clean($this->input->get_post('id'));

		//生产企业上线情况统计
		if($name=="countEntNum"){
			$onlineNum = $this->ReportRepository->countEntNum();
			$offlineNum= 496 - $onlineNum;
			$result= [
				["Status"=>"已上线", "EntNum"=>$onlineNum,],
				["Status"=>"未上线", "EntNum"=>$offlineNum,],
			];
			//$title = "生产企业上线情况统计";
			$data = $this->Echarts->adaptResult($result,"Status","EntNum","统计");
		}

		//生产企业数量 按地域统计
		if($name=="countEntNumGroupByRegion"){
			$result = $this->ReportRepository->countEntNumGroupByRegion();
			//$title = "生产企业上线地域统计";
			$data = $this->Echarts->adaptResult($result,"County","EntNum","上线企业");
		}

		//生产企业数量 按分类统计
		if($name=="countEntNumGroupByCate"){
			$result = $this->ReportRepository->countEntNumGroupByCate();
			$data = $this->Echarts->adaptResult($result,"Cate","EntNum","统计");
		}

		//生产批次数量 按月统计
		if($name=="countBatNumGroupByMonth"){
			$result = $this->ReportRepository->countBatNumGroupByMonth();
			$data = $this->Echarts->adaptResult($result,"ProductMonth","BatNum","统计");
		}

		//生产批次数量 按天统计
		if($name=="countBatNumGroupByDay"){
			$result = $this->ReportRepository->countBatNumGroupByDay();
			$data = $this->Echarts->adaptResult($result,"Day","BatNum","批次数量");
		}
		//企业市场占有率
		if($name=="countMarketRateGroupByEnt"){
			$result = $this->ReportRepository->countMarketRateGroupByEnt();
			$data = $this->Echarts->adaptResult($result,"EntName","MarketRate","市场占有率");
		}

		if($name=="countRiskLevGroupByEntType"){
			$data = [
				"title"=>"某站点用户访问来源",
				"legend"=>['食品生产企业','食品销售企业','餐饮服务企业'],
				"xAxis"=> ["A","B","C","D"],
				"series"=>[
					[
						"name"=>"食品生产企业",
						"data"=>[1,1,0,3],
					],
					[
						"name"=>"食品销售企业",
						"data"=>[0,2,0,1],
					],
					[
						"name"=>"餐饮服务企业",
						"data"=>[1,0,0,2]
					],
				]
			];
		}
		if($name==""){
			$data["productionNum"]	= $this->ReportRepository->countProductNum();	//获得累计生产量
			$data["entNum"]			= $this->ReportRepository->countEntNum();		//企业上线总数
			$data["productBatNum"]	= $this->ReportRepository->countProductBatNum();	//生产批次总数
			$data["productTypeNum"]	= $this->ReportRepository->countProductTypeNum();	//产品类型总数
			$data["productResNum"]	= $this->ReportRepository->countProductResNum();	//原料台账总数
		}

		switch ($type){
			case "map":
				//$this->showMap($result,$xAxis,$yAxis,$yLabel,$title,$id);
				echo $this->Echarts->show("map",$title,$data,$id);
				break;
			case "pie":
				//$this->showPie($result,$xAxis,$yAxis,$yLabel,$title,$id);
				echo $this->Echarts->show("pie",$title,$data,$id);
				break;
			case "bar":
				//$this->showBar($result,$xAxis,$yAxis,$yLabel,$title,$id);
				echo $this->Echarts->show("bar",$title,$data,$id);
				break;
			case "line":
				//$this->showLine($result,$xAxis,$yAxis,$yLabel,$title,$id);
				echo $this->Echarts->show("line",$title,$data,$id);
				break;
			case "mbar":
				echo $this->Echarts->show("Mbar",$title,$data,$id);
				break;
			default:
				echo $this->Echarts->show("json",null,$data,null);
		}
	}
}
