<?php

/**
 * 报表类
 * Class Inspect
 */
class ReportRepository extends CI_Model
{
	protected $entId;
	public function __construct()
	{
		parent::__construct();
		$this->db	= $this->load->database('default', true);
		$this->db->cache_on();		//开启缓存
	}

	public function setEnt($EntId){
		$this->entId = $EntId;
		return $this;
	}

	/**
	 * 获取所有企业或某一企业 生产批次总量
	 * @param int $EntId
	 * @return int
	 */
	public function countBatNum($EntId=0){
		if(empty($EntId)){
			$sql = "select count(*) as t from productBat";
			$row = $this->db->query($sql)->row_array();
		}else{
			$sql = "select count(*) as t from productBat where EntId = ?";
			$row = $this->db->query($sql,[$EntId])->row_array();
		}

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}



	/**
	 * 获取 企业-生产批次数量列表 默认5条
	 * @param int $Num
	 * @return array
	 */
	public function countBatNumGroupByEnt($Num=5){

		$sql = "select top $Num 
		b.EntId as EntId,b.Name as EntName,count(a.BatId) as BatNum from productBat as a,productEnt as b
		where a.EntId = b.EntId
		group by b.EntId,b.Name
		order by BatNum desc";
		return $this->db->query($sql)->result_array();
	}


	/**
	 * 获取所有企业或某一企业 指定时间段内默认一年  月份-批次数量列表
	 * @param $EntId
	 * @return
	 */
	public function countBatNumGroupByMonth($EntId=0,$start=null,$final=null){
		if(empty($start)){
			//去年末
			$start = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d"),date("Y")-1));
		}

		if(empty($final)){
			//今年末
			$final = date("Y-m-d",mktime(23,59,59,date("m"),date("d"),date("Y")));
		}

		if($EntId==0) {
			$sql = "select convert(char(7), ProductDateTime, 120) as ProductMonth,count(BatId) as BatNum from productBat
		where ProductDateTime between '$start' and '$final'
		group by convert(char(7), ProductDateTime, 120)
		order by ProductMonth asc";
			$result = $this->db->query($sql)->result_array();
		}else{
			$sql = "select convert(char(7), ProductDateTime, 120) as ProductMonth,count(BatId) as BatNum from productBat
		where ProductDateTime between '$start' and '$final' and EntId = ?
		group by convert(char(7), ProductDateTime, 120)
		order by ProductMonth asc
		";
			$result = $this->db->query($sql,[$EntId])->result_array();
		}

		return $result;
	}

	/**
	 * 获取所有企业或某一企业 指定时间段内默认一月  天-批次数量列表
	 * @param int $EntId
	 * @param null $start 开始时间
	 * @param null $final 结束时间
	 * @return
	 */
	public function countBatNumGroupByDay($EntId=0,$start=null,$final=null){

		if(empty($start)){
			if (date("n") == 1) {
				$tmpYear 	= date ("Y") - 1;
				$tmpMonth 	= 12;
				$tmpDay 	= date("d");
			}else{
				$tmpYear 	= date ("Y");
				$tmpMonth 	= date ("n") - 1;
				$tmpDay 	= date("d");
			}

			$start = date("Y-m-d",strtotime("$tmpYear-$tmpMonth-$tmpDay"));
		}
		if(empty($final)){
			$final = date("Y-m-d");
		}



		$date = $start;
		$tmp = [];
		while($date<= $final){
			$tmp[] = "('".$date."')";
			$date = date("Y-m-d",strtotime("$date +1 day"));
		}
		$joinDateRange = "right join (
            select * from (values".join($tmp,",").") t(Day)
        ) as b";

		if($EntId==0) {
			/*
			$sql = "select convert(char(10), ProductDateTime, 120) as ProductDateTime,count(BatId) as BatNum from productBat
			where ProductDateTime between '$start' and '$final'
			group by convert(char(10), ProductDateTime, 120)
			order by ProductDateTime asc";
			*/
			$sql = "
			select 
			b.Day,
			count(a.BatId) as BatNum
			from productBat as a			
			$joinDateRange
			on convert(char(10), a.ProductDateTime, 120) = b.Day
			group by b.Day
			order by b.Day";


			$result = $this->db->query($sql)->result_array();
		}else{
			/*
			$sql = "select convert(char(10), ProductDateTime, 120) as ProductDateTime,count(BatId) as BatNum from productBat
				where ProductDateTime between '$start' and '$final' and EntId = ?
				group by convert(char(10), ProductDateTime, 120)
				order by ProductDateTime asc";
			*/
			$sql = "
			select 
			b.Day,
			count(a.BatId) as BatNum
			from productBat as a			
			$joinDateRange
			on convert(char(10), a.ProductDateTime, 120) = b.Day
			where a.EntId = ?
			group by b.Day
			order by b.Day";
			$result = $this->db->query($sql,[$EntId])->result_array();
		}

		return $result;
	}

	/**
	 * 获取所有企业或某一企业 累计生产总量 Accumulate Acc
	 * @param int $EntId
	 * @return int
	 */
	public function countProductNum($EntId=0){
		if($EntId==0){
			$sql = "SELECT sum(ProductNum) as t FROM productBat";
			$row = $this->db->query($sql)->row_array();
		}else{
			$sql = "SELECT sum(ProductNum) as t FROM productBat where EntId = ?";
			$row = $this->db->query($sql,[$EntId])->row_array();
		}

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 获取 企业-生产产品数量列表 默认5条
	 * @param int $Num
	 * @return array
	 */
	public function countProductNumGroupByEnt($Num=5){

		$sql = "select top $Num 
		b.EntId as EntId,b.Name as EntName,sum(a.ProductNum) as ProductNum from productBat as a,productEnt as b
		where a.EntId = b.EntId
		group by b.EntId,b.Name
		order by ProductNum desc";
		return $this->db->query($sql)->result_array();
	}
	/**
	 * 获取所有企业或某一企业 生产批次总量
	 * @param int $EntId
	 * @return int
	 */
	public function countProductBatNum($EntId=0){
		return $this->countBatNum($EntId);
	}

	/**
	 * 获取所有企业或某一企业 产品类型总量
	 * @param int $EntId
	 * @return int
	 */
	public function countProductTypeNum($EntId=0){
		if($EntId==0){
			$sql = "select count(*) as t from productPrice";
			$row = $this->db->query($sql)->row_array();
		}else{
			$sql = "select count(*) as t from productPrice where EntId = ?";
			$row = $this->db->query($sql,[$EntId])->row_array();
		}

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 获取所有企业或某一企业 原料台账总量
	 * @param int $EntId
	 * @return int
	 */
	public function countProductResNum($EntId=0){
		if($EntId==0){
			$sql = "select count(*) as t from productRes";
			$row = $this->db->query($sql)->row_array();
		}else{
			$sql = "select count(*) as t from productRes where EntId = ?";
			$row = $this->db->query($sql,[$EntId])->row_array();
		}

		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}


	/**
	 * 统计 上线企业总量
	 */
	public function countEntNum(){
		$sql = "select count(*) as t from productEnt";
		$row = $this->db->query($sql)->row_array();
		$total = 0;
		if (!empty($row['t'])){
			$total = $row['t'];
		}
		return $total;
	}

	/**
	 * 按区域 统计企业数量
	 */
	public function countEntNumGroupByRegion(){
		$sql = "select b.county as County, count(ID) as EntNum from productEnt as a
        right join (
            select * from (VALUES('盐湖区'),('临猗县'),('万荣县'),('闻喜县'),('稷山县'),
            ('新绛县'),('绛县'),('垣曲县'),('夏县'),('平陆县'),('芮城县'),('永济市'),('河津市')) t(County)
        ) as b
        on a.County = b.County
        group by b.County";

		return $this->db->query($sql)->result_array();
	}

	/**
	 * 按类型 统计企业数量
	 */
	public function countEntNumGroupByCate(){
		$sql = "select Cate, count(ID) as EntNum from productEnt group by Cate";
		return $this->db->query($sql)->result_array();
	}


	/**
	 * 统计 企业市场占有率
	 */
	public function countMarketRateGroupByEnt(){

		$sql = "select EntName,cast(round(ProductNum/total,2)*100 as int) as MarketRate from(
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
		$result = $this->db->query($sql)->result_array();

		return $result;
	}
}