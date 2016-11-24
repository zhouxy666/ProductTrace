<?php

/**
 * 检查批次类
 * Class Inspect
 */
class InspectRepository extends CI_Model
{
	protected $entId;
	public function __construct()
	{
		parent::__construct();
		$this->db	= $this->load->database('default', true);
	}
	public function setEnt($EntId){
		$this->entId = $EntId;
		return $this;
	}

	/**
	 * 获取企业 待检查批次的总数
	 * @return int
	 */
	public function getInspectedNum(){
		$sql = "select count(*) as t from productInspect where EntId = ?";
		$row = $this->db->query($sql,[$this->entId])->row_array();

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 获取企业 待检测批次总数
	 * @return int
	 */
	public function getTotal(){
		$sql = "select count(*) as t from productBat where EntId = ?";
		$row = $this->db->query($sql,[$this->entId])->row_array();

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 获取企业 待检测批次列表
	 */
	public function getList($from,$to){
		/*
		$sql = <<<EOF
			select * from ( 
			select
			a.EntId, --企业ID
			a.BatId , --批次ID
			a.BatName, --批次名称
			a.ProductName, --产品名称
			a.ProductNum, --生产数量
			a.ProductDateTime, --产品生产日期
			b.SampleNum, --抽检数量
			b.QualifiedNum, --合格数量
			b.InspectDateTime, --检测时间
			b.QualifiedRate, --合格率
			ROW_NUMBER() OVER (order by a.BatId desc) as rn 
			from productBat as a
			left join productInspect as b on b.EntId = a.EntId and b.BatId = a.BatId --联合检测信息
			where a.EntId = ?
			) as tt where tt.rn>? and tt.rn<=?
EOF;
		*/
		$sql = "select * from ( 
			select
			EntId,BatId,BatName,ProductName,ProductNum,ProductDateTime,SampleNum,QualifiedNum,InspectDateTime,QualifiedRate,
			ROW_NUMBER() OVER (order by BatId desc) as rn 
			from productBat
			where EntId = ?
			) as tt where tt.rn>? and tt.rn<=?";
		$result = $this->db->query($sql,[$this->entId,$from,$to])->result();
		return $result;
	}

	/**
	 * 获取所有生产批次的合格率
	 * x轴 生产日期
	 * y轴 合格率
	 */
	public function getAllBatQualifiedRate(){
		$sql = "select 
		sum(QualifiedNum)*100/sum(SampleNum) as QualifiedRate,
		CONVERT(varchar(100), ProductDateTime, 102) as ProductDateTime 
		from productBat group by ProductDateTime";
		return $this->db->query($sql)->result();
	}
}