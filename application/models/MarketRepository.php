<?php

/**
 * 产值类
 * Class Inspect
 */
class MarketRepository extends CI_Model
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
	 * 获取所有企业市场占有率
	 */
	public function getAllEntMarketRate(){

		$sql = "select EntName,cast(round(ProductNum/total,2)*100 as int) as rate from(
		select
		EntId, 
		EntName,
		EntCate,
		sum(ProductNum) as ProductNum,
		(select sum(ProductNum) from productBat,ProductEnt where productBat.EntId = productEnt.EntId and productEnt.Cate = t.EntCate) as total
		from (
		select a.EntId as EntId,a.Name as EntName,a.Cate as EntCate,b.ProductNum as ProductNum from productEnt as a ,productBat as b
		where a.EntId = b.EntId
		) as t
		group by EntId,EntName,EntCate
		) as q";
		$result = $this->db->query($sql)->result();

		return $result;
	}
}