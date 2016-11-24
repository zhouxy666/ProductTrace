<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspect extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->db               = $this->load->database('default', true);
		$this->db_suyuan_multi  = $this->load->database('生产溯源多平台', true);
		$this->load->model("Gis");
		$this->load->model("Region");				//加载行政区域模型
		$this->load->model("Ent");					//加载企业信息模型
		$this->load->model("ProductRepository");	//加载生产代码库
		$this->load->model("InspectRepository");	//加载检测代码库
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->library('pagination');
	}

	/**
	 * 获取所有企业所有批次的合格率
	 * 按生产日期分组 统计合格率
	 */
	public function json(){
		$result 	= $this->InspectRepository->getAllBatQualifiedRate();
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
	}

	/**
	 * 获取当前企业 最近20批的合格率
	 * @param $entId
	 */
	public function chart($entId){
		$sql = "select * from (
				select top 20 
				BatId,
				SampleNum,
				QualifiedNum,
				FailureNum,
				QualifiedRate,
				CONVERT(varchar(100), ProductDateTime, 102) as ProductDateTime 
				from productBat
				where EntId =? 
				order by BatId desc) as t
				order by t.BatId asc
				";

		$result = $this->db->query($sql,[$entId])->result();


		$x 	= "ProductDateTime"; 	//x轴字段
		$s1 = "QualifiedRate";		//系列1字段
		$xAxis 	= [];
		$series1= [];
		foreach($result as $row){
			$xAxis	[] = "'".$row->$x."'";
			$series1[] = $row->$s1;
		}

		$data['xAxis'] 	= $xAxis;
		$data['series1']= $series1;
		$data['result'] = $result;
		$this->load->view("product/Inspect/chart",$data);
	}


	/**
	 * 查询企业批次
	 * @param string $enterprise_id
	 */
	public function index($enterprise_id=""){
		$regions 	= $this->Region->getAll();
		$region 	= $this->security->xss_clean($this->input->get_post('region'));


		if(empty($region)){

		}else{

		}

		$tree 	= $this->Ent->getTree();

		if(empty($enterprise_id)){
			//show_error("企业ID不能为空",500,"系统发生了一个错误，原因如下：");
			$result			= [];
			$page 			= null;
			$enterprise_id  = null;
			$this->load->view("product/inspect/index",compact("regions","tree","result","page","enterprise_id"));
			return;
		}

		$pageNum    	= $this->security->xss_clean($this->input->get_post('per_page'));
		$productBatchNum= $this->ProductRepository->setEnt($enterprise_id)->getTotal();				//生产批次总数
		$inspectBatchNum= $total = $this->InspectRepository->setEnt($enterprise_id)->getTotal();	//检测批次总数

		$pageSize       = 20;   //每页显示记录数
		$pageMax        = ceil($total/$pageSize);           //最大页码
		//页码过滤
		$pageNum        = (empty($pageNum))?1:$pageNum;     //当前页码
		$pageNum        = ($pageNum>$pageMax)?$pageMax:$pageNum;
		$pageNum        = ($pageNum<1)?1:$pageNum;
		$from           = ($pageNum-1) * $pageSize;
		$to             = $pageNum * $pageSize;
		$to             = ($to>$total)?$total:$to;

		$config['base_url']             = site_url(__CLASS__."/enterprise/$enterprise_id");
		$config['uri_segment']          = 4;
		$config['total_rows']           = $total;
		$config['per_page']             = $pageSize;
		$config['enable_query_strings'] = true;
		$config['page_query_string']    = true;
		$config['use_page_numbers']     = true;
		$param = ['first_link','last_link','prev_link','next_link','full_tag_open','full_tag_close',
			'first_tag_open','first_tag_close','last_tag_open','last_tag_close',
			'next_tag_open','next_tag_close','prev_tag_open','prev_tag_close',
			'cur_tag_open','cur_tag_close','num_tag_open','num_tag_close'
		];
		foreach($param as $k){
			$config[$k] = config_item($k);
		}
		$this->pagination->initialize($config);

		$page["total"]  = $total;
		$page["from"]   = $from + 1;
		$page["to"]     = $to;
		$page["link"]   = $this->pagination->create_links();

		/*
		$result1 = $this->ProductRepository->setEnt($enterprise_id)->getList($from,$to);
		//从协同表中获取合格率信息
		$result2 = $this->db->query("select * from productInspect where EntId =?",[$enterprise_id])->result();
		//将两个$result 进行合并
		$result = $this->combineResult($result1,$result2);
		*/

		$result = $this->InspectRepository->setEnt($enterprise_id)->getList($from,$to);

		$this->load->view("product/inspect/index",compact("regions","tree","result","page","enterprise_id","productBatchNum","inspectBatchNum"));
	}

	/**
	 * 同步批次数据
	 * @param $EnterpriseId
	 */
	public function syncBatch($EnterpriseId){
		if(empty($EnterpriseId)){
			show_error("企业ID不能为空",500,"系统发生了一个错误，原因如下：");
		}

		//获取当前企业下所有批次信息
		$result = $this->Batch->setEnt($EnterpriseId)->getAll();

		foreach($result as $row){
			$data = [
				"EnterpriseId"=>$EnterpriseId,
				"BatchId"=>$row->PK_REPBID,
				"SampleNum"=>$row->REPB_Nums,			//默认全检
				"QualifiedNum"=>$row->REPB_Nums,		//默认全合格
				"FailureNum"=>0,
				"QualifiedRate"=>100,
				"ProductName"=>$row->CPI_Name,			//产品名称
				"ProductNum"=>$row->REPB_Nums,			//生产数量
				"ProductDateTime"=>$row->REPB_PDate,	//生产时间
				"InsepctDateTime"=>date("Y-m-d H:i:s"),	//检测时间
			];
			/*
			$row = $this->db->query("select * from productInspect where EnterpriseId=? and BatchId=?",[$EnterpriseId,$row["PK_REPBID"]])->row_array();
			if(!empty($row["ID"])){
				//$this->db->where("ID",$row["ID"])->update("ProductInspect",$data);
			}else{
				$this->db->insert("ProductInspect",$data);
			}
			*/
			$this->db->insert("ProductInspect",$data);
		}

		echo json_encode(["message"=>"同步批次数据成功！","status"=>1]);
	}

	/**
	 * 抽检企业批次
	 * @param string $enterprise_id 企业编号
	 * @param string $batch_id 批次编号
	 */
	public function batch($enterprise_id="",$batch_id=""){
		if(empty($enterprise_id)){
			show_error("企业ID不能为空",500,"系统发生了一个错误，原因如下：");
		}
		if(empty($batch_id)){
			show_error("批次ID不能为空",500,"系统发生了一个错误，原因如下：");
		}
		$single_db = "";
		if($enterprise_id<0) {
			if ($enterprise_id == -82) {
				$id = "082";
			} else {
				$id = sprintf("%02d", abs($enterprise_id));
			}
			$single_db = "生产溯源单平台_$id";
		}
		//查询企业信息
		$sql1 = "select * from SY_Config where PK_CID = ?";
		//查询批次信息
		$sql2 = "select * from SY_Rec_ProducBatches where PK_REPBID =? and cid= ?";

		if($enterprise_id<0){
			if(empty($single_db)){
				show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
			}
			$this->db_suyuan_single  = $this->load->database($single_db, true);

			$enterprise = $this->db_suyuan_single->query($sql1,[1])->row_array();
			$batch = $this->db_suyuan_single->query($sql2,[$batch_id,1])->row_array();
		}else{
			$enterprise = $this->db_suyuan_multi->query($sql1,[$enterprise_id])->row_array();
			$batch = $this->db_suyuan_multi->query($sql2,[$batch_id,$enterprise_id])->row_array();
		}

		$this->load->view("ProductInspect/batch",compact("enterprise","batch","enterprise_id","batch_id"));
	}

	/**
	 * 保存检测结果
	 */
	public function save(){
		$EntId			= $this->security->xss_clean($this->input->get_post('EntId'));
		$BatId			= $this->security->xss_clean($this->input->get_post('BatId'));
		$SampleNum		= $this->security->xss_clean($this->input->get_post('SampleNum'));
		$QualifiedNum	= $this->security->xss_clean($this->input->get_post('QualifiedNum'));
		$FailureNum		= 0;
		if(($SampleNum - $QualifiedNum)>0){
			$FailureNum		= $SampleNum - $QualifiedNum;
		}

		$InspectDateTime	= date("Y-m-d H:i:s");

		$data = [
			"EntId"=>$EntId,
			"BatId"=>$BatId,
			"SampleNum"=>$SampleNum,
			"QualifiedNum"=>$QualifiedNum,
			"FailureNum"=>$FailureNum,
			"QualifiedRate"=>ceil($QualifiedNum*100/$SampleNum),
			"InspectDateTime"=>$InspectDateTime,
		];
		$row = $this->db->query("select * from productBat where EntId=? and BatId=?",[$EntId,$BatId])->row_array();
		if(!empty($row["ID"])){
			$this->db->where("ID",$row["ID"])->update("productBat",$data);
		}else{
			$this->db->insert("productBat",$data);
		}

		echo json_encode(["message"=>"保存成功","status"=>1]);
	}
}