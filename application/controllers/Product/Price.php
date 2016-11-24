<?php

/**
 * select t.EntId,sum(Price) as Price from (
select a.EntId,a.ProductId,a.ProductNum,b.ProductPrice,a.ProductNum*b.ProductPrice as Price
from (
select EntId,ProductId,sum(ProductNum) as ProductNum from productBat
where ProductDateTime between '2016-07-01' and '2016-08-01'
group by EntId,ProductId) as a
join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
) as t
group by t.EntId
 */

class Price extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);

		$this->load->model("Region");             	//加载行政区域模型
		$this->load->model("Ent");                  //加载企业信息模型
		$this->load->model("ProductRepository");    //加载生产代码库
		$this->load->model("PriceRepository");    	//加载价格代码库
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}

	public function json(){
		$result 	= $this->PriceRepository->getAllEntTotalPrice();
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
	}
	/**
	 * 查询企业批次
	 * @param int $enterprise_id 企业ID
	 */
	public function index($enterprise_id=0){
		$tree = $this->Ent->getTree();

		if(empty($enterprise_id)){
			$result			= [];
			$page 			= null;
			$enterprise_id  = null;
			$this->load->view("product/price/index",compact("tree","result","page","enterprise_id"));
			return;
		}

		$result 		= $this->PriceRepository->getAll($enterprise_id);
		$curMonthPrice 	= $this->PriceRepository->getCurMonthPrice($enterprise_id);
		$accPrice 		= $this->PriceRepository->getAccPrice($enterprise_id);
		$this->load->view("product/price/index",compact("tree","result","enterprise_id","curMonthPrice","accPrice"));
	}

	/**
	 * 保存检测结果
	 */
	public function save(){
		$EntId			= $this->security->xss_clean($this->input->get_post('EntId'));
		$ProductId		= $this->security->xss_clean($this->input->get_post('ProductId'));
		$ProductPrice	= $this->security->xss_clean($this->input->get_post('ProductPrice'));

		$data = [
			"EntId"=>$EntId,
			"ProductId"=>$ProductId,
			"ProductPrice"=>$ProductPrice,
		];
		$row = $this->db->query("select * from productPrice where EntId=? and ProductId=?",[$EntId,$ProductId])->row_array();
		if(!empty($row["ID"])){
			$this->db->where("ID",$row["ID"])->update("productPrice",$data);
		}else{
			$this->db->insert("productPrice",$data);
		}

		echo json_encode(["message"=>"保存成功","status"=>1]);
	}

}