<?php

/**
 * 价格批次类
 * Class Inspect
 */
class PriceRepository extends CI_Model
{
	protected $entId;
	public function __construct()
	{
		parent::__construct();
		$this->db	= $this->load->database('default', true);
	}

	/**
	 * 获取企业 产品价格列表
	 * @param int $EntId
	 * @return
	 */
	public function getAll($EntId){
		$sql = "select ProductId,ProductName,ProductBrand,ProductPrice from productPrice where EntId = ?";
		return $this->db->query($sql,[$EntId])->result();
	}

	/**
	 * 获取所有企业或者某一企业 本月产值
	 * @param int $EntId
	 * @return int
	 */
	public function getCurMonthPrice($EntId=0){
		$start = date("Y-m-d",mktime(0, 0 , 0,date("m"),1,date("Y")));
		$final = date("Y-m-d",mktime(23,59,59,date("m"),date("t"),date("Y")));
		/*
		$sql = "
		select sum(Price) as Price from (
		select a.EntId,a.ProductId,a.ProductNum,b.ProductPrice,a.ProductNum*b.ProductPrice as Price 
		from (
		select EntId,ProductId,sum(ProductNum) as ProductNum from productBat 
		where ProductDateTime between '$start' and '$final'
		and EntId = ?
		group by EntId,ProductId) as a
		join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId 
		) as t
		group by t.EntId";
		*/
		if($EntId == 0 ){
			$sql = "select sum(ProductNum*ProductPrice) as Price from ( 
				select a.EntId,a.ProductId,a.ProductNum as ProductNum,b.ProductPrice as ProductPrice
				from productBat as a
				join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
				where a.ProductDateTime between '$start' and '$final'
			) as t";
			$row = $this->db->query($sql)->row();
		}else{
			$sql = "select sum(ProductNum*ProductPrice) as Price from ( 
				select a.EntId,a.ProductId,a.ProductNum as ProductNum,b.ProductPrice as ProductPrice
				from productBat as a
				join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
				where a.EntId = ? and a.ProductDateTime between '$start' and '$final'
			) as t";
			$row = $this->db->query($sql,[$EntId])->row();
		}

		if(!empty($row->Price)){
			return $row->Price;
		}else{
			return 0;
		}
	}

	/**
	 * 获取某一企业  累计产值
	 * @param int $EntId
	 * @return int
	 */
	public function getAccPrice($EntId=0){
		/*
		$sql = "
		select sum(Price) as Price from (
		select a.EntId,a.ProductId,a.ProductNum,b.ProductPrice,a.ProductNum*b.ProductPrice as Price 
		from (
		select EntId,ProductId,sum(ProductNum) as ProductNum from productBat 
		where EntId = ?
		group by EntId,ProductId) as a
		join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId 
		) as t
		group by t.EntId";
		*/
		if($EntId == 0){
			$sql = "select sum(ProductNum*ProductPrice) as Price from ( 
			select a.EntId,a.ProductId,a.ProductNum as ProductNum,b.ProductPrice as ProductPrice
			from productBat as a
			join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
			) as t";
			$row = $this->db->query($sql)->row();
		}else{
			$sql = "select sum(ProductNum*ProductPrice) as Price from ( 
				select a.EntId,a.ProductId,a.ProductNum as ProductNum,b.ProductPrice as ProductPrice
				from productBat as a
				join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
				where a.EntId = ?
			) as t";
			$row = $this->db->query($sql,[$EntId])->row();
		}

		if(!empty($row->Price)){
			return $row->Price;
		}else{
			return 0;
		}
	}

	/**
	 * 获取所有企业累计产值
	 */
	public function getAllEntTotalPrice(){
		/*
		$sql = "
		select t.EntName,sum(Price) as Price from (
		select c.Name as EntName,a.EntId,a.ProductId,a.ProductNum,b.ProductPrice,a.ProductNum*b.ProductPrice as Price 
		from (
		select EntId,ProductId,sum(ProductNum) as ProductNum from productBat 
		group by EntId,ProductId) as a
		join productPrice as b on a.EntId= b.EntId and a.ProductId = b.ProductId
		join productEnt as c on c.EntId = a.EntId
		) as t
		group by t.EntId,t.EntName";
		*/
		$sql = "select t.EntName,sum(t.ProductNum*t.ProductPrice) as Price from (
		  SELECT
			a.EntId AS EntId,
			c.Name As EntName,
			a.ProductId AS ProductId,
			ProductNum,
			b.ProductName AS ProductName,
			ProductPrice
		  FROM productBat AS a, productPrice AS b,productEnt As c
		  WHERE a.EntId = b.EntId AND a.ProductId = b.ProductId And a.EntId = c.EntId
		) as t
		GROUP BY t.EntName";
		return $this->db->query($sql)->result();
	}

}