<?php

/**
 * 企业库 生产批次类
 * Class Batch
 */
class ProductRepository extends CI_Model
{
	protected $enterpriseId;
	protected $db;
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 设置企业ID
	 * @param int $EntId 监管系统企业ID
	 * @return $this
	 * @throws Exception
	 */
	public function setEnt($EntId){
		$this->defaultDB  = $this->load->database("default", true);

		//获取监管端企业信息
		$ent = $this->defaultDB->query("select * from productEnt where EntId =?",[$EntId])->row();

		if(!$ent){
			throw new Exception("无法获取监管企业信息");
		}
		//连接溯源端数据库
		$this->db  	= $this->load->database($ent->DBLink, true);

		if($EntId < 0 ){
			//溯源单平台 真实企业ID
			$this->enterpriseId = 1;
		}else{
			//溯源多平台 真实企业ID
			$this->enterpriseId = $EntId;
		}


		/*
		if($EnterpriseId<0) {
			if ($EnterpriseId == -82) {
				$id = "082";
			} else {
				$id = sprintf("%02d", abs($EnterpriseId));
			}
			//连接单平台数据库
			$this->db  = $this->load->database("生产溯源单平台_$id", true);
		}else{
			$this->db  = $this->load->database('生产溯源多平台', true);
		}*/
		return $this;
	}
	/**
	 * 从不同数据库
	 * 获取生产批次总数
	 * @return int
	 */
	public function getTotal(){
		$sql = "select count(a.PK_REPBID) as t 
		from SY_Rec_ProducBatches as a
        join SY_Code_ProductInfo as b on b.PK_CPIID = a.CPIID 
        where a.CID = ?";

		$row = $this->db->query($sql,[$this->enterpriseId])->row_array();

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 获取某一企业 生产批次列表
	 * @param int $from
	 * @param int $to
	 * @return
	 */
	public function getList($from,$to){
		$sql = <<<EOF
        select * from ( 
        select 
        a.PK_REPBID , --批次ID
        a.REPB_Name, --批次名称
        a.CPIID, --产品ID
        a.REPB_PDate, --产品生产日期
        a.CID , --企业ID
        a.RER_ID, --规则ID
        a.REPB_StartNums, --批次起始数
        a.REPB_EndNums, --批次终止数
        a.REPB_Nums, --批次数量
        a.REPB_ISCheck, --是否审核
        a.REPB_DateTime, --新增时间
        a.REPB_DateTimeOK, --生成时间
        c.CPI_Name, --产品名称
        ROW_NUMBER() OVER (order by PK_REPBID desc) as rn 
        from SY_Rec_ProducBatches as a
        join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID --联合产品信息
        where a.CID = ?
        ) as tt where tt.rn>? and tt.rn<=?
EOF;
		$result = $this->db->query($sql,[$this->enterpriseId,$from,$to])->result();

		return $result;
	}

	/**
	 * 查询SQL
	 * @param string $sql
	 * @param bool $binds
	 * @param null $return_object
	 * @return
	 */
	public function query($sql, $binds = FALSE, $return_object = NULL){
		return $this->db->query($sql, $binds, $return_object)->result();
	}

	/**
	 * 获取企业所有生产批次
	 * @return array
	 */
	public function getAll(){
		$sql = "select
		a.PK_REPBID , --批次ID
        a.REPB_Name, --批次名称
        a.CPIID, --产品ID
        a.REPB_PDate, --产品生产日期
        a.CID , --企业ID
        a.RER_ID, --规则ID
        a.REPB_StartNums, --批次起始数
        a.REPB_EndNums, --批次终止数
        a.REPB_Nums, --批次数量
        a.REPB_ISCheck, --是否审核
        a.REPB_DateTime, --新增时间
        a.REPB_DateTimeOK, --生成时间
        b.CPI_Name --产品名称
		from SY_Rec_ProducBatches as a 
		join SY_Code_ProductInfo as b on b.PK_CPIID = a.CPIID where a.CID = ?";

		return $this->db->query($sql,[$this->enterpriseId])->result();
	}
}