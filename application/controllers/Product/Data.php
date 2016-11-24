<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller
{
	protected $CID;
	protected $EntDB;		//企业数据库
	protected $AdmDB;		//监管数据库
    public function __construct()
    {
        parent::__construct();
		$this->AdmDB			= $this->load->database("default", true);	//连接监管端数据库
        //$this->db               = $this->load->database('default', true);
        //$this->db_suyuan_multi  = $this->load->database('生产溯源多平台', true);
		//$this->load->model("Region");	//加载行政区域模型
		//$this->load->model("Batch");	//加载批次模型
		//$this->load->model("Gis");	//加载Gis模型
    }

	/**
	 * 生成同步任务
	 */
	public function index(){
		$result = $this->AdmDB->query("select * from productEnt")->result();
		$i = 0;

		foreach($result as $row){
			if($row->EntId>0 || $row->EntId==-1){
				$i++;
				$second = rand(0,60); 	//随机生成秒
				//$minute = rand(0,60);	//随机生成分钟
				//每1分钟 出现指定秒 同步一次批次信息
				echo " {\"cron\":\"$second 0/1 * * * *\",\"url\":\"http://127.0.0.1/index.php/product/Data/SyncBat/$row->EntId\",\"id\":$i},\n";
			}
		}

		foreach($result as $row){
			if($row->EntId>0 || $row->EntId==-1){
				$i++;
				$second = rand(0,60); 	//随机生成秒
				//$minute = rand(0,60);	//随机生成分钟
				//每2分钟 出现指定秒 同步一次产品信息
				echo " {\"cron\":\"$second 0/2 * * * *\",\"url\":\"http://127.0.0.1/index.php/product/Data/SyncProduct/$row->EntId\",\"id\":$i},\n";
			}
		}

		foreach($result as $row){
			if($row->EntId>0 || $row->EntId==-1){
				$i++;
				$second = rand(0,60); 	//随机生成秒
				//$minute = rand(0,60);	//随机生成分钟
				//每1分钟 出现指定秒 同步一次原料信息
				echo " {\"cron\":\"$second 0/1 * * * *\",\"url\":\"http://127.0.0.1/index.php/product/Data/SyncRes/$row->EntId\",\"id\":$i},\n";
			}
		}
	}
	/**
	 * 同步多平台企业基本信息
	 */
	public function SyncEntFromMulti(){
		$syncNum = 20; //每次同步20条
		$p = read_file(FCPATH."data/Ent.php");
		if($p === false){
			show_error("无法读取同步进度",500,"系统发生了一个错误");
		}
		$source 	= "SY_Config";
		$source_idx = "PK_CID";
		$dest 		= "ProductEnt";
		$dest_idx 	= "EntID";
		$map = [
			"PK_CID"=>"EntId",				//企业编号
			"C_Name"=>"Name",				//企业名称
			"C_Legalper"=>"LegalPerson", 	//企业法人
			"C_Address"=>"Addr",			//企业地址
			"C_Tel"=>"Tel",					//企业联系电话
			"C_Fax"=>"Fax",					//企业传真
			"C_WWW"=>"Url",					//企业网址
			"C_Licence"=>"Licence",			//企业生产许可证
			"fm_Province"=>"Province",		//企业所在省
			"fm_City"=>"City",				//企业所在市
			"fm_County"=>"County",			//企业所在县
		];

		$sql = "select top $syncNum ".join(array_keys($map),",")." from $source where $source_idx > $p order by $source_idx asc";
		$result = $this->db_suyuan_multi->query($sql)->result_array();

		if(empty($result)){
			show_error("源数据库已全部同步完毕",500);
		}

		$data = [];
		foreach($result as $row){
			$tmp = [];
			foreach($map as $k=>$v){
				$tmp[$v]=$row[$k];
			}
			$this->on_map($tmp,$row);
			$data[] = $tmp;
		}

		$this->db->insert_batch($dest,$data);

		$row = $this->db->query("select top $syncNum max($dest_idx) as p from $dest")->row_array();
		if(!write_file(FCPATH."data/Ent.php",$row['p'])){
			show_error("无法保存同步进度",500,"系统发生了一个错误");
		}
		//$this->_afterSyncEnt($data);
	}

	/**
	 * 同步单平台企业基本信息
	 * @param int $EntId 单平台企业数据库ID
	 */
	public function SyncEntFromSingle($EntId){
		$syncNum = 20; //每次同步20条
		if(file_exists(FCPATH."data/Ent_$EntId.php")){

		}
		$p = read_file(FCPATH."data/Ent.php");
		if($p === false){
			show_error("无法读取同步进度",500,"系统发生了一个错误");
		}
		$source 	= "SY_Config";
		$source_idx = "PK_CID";
		$dest 		= "ProductEnt";
		$dest_idx 	= "EntID";
		$map = [
			"PK_CID"=>"EntId",				//企业编号
			"C_Name"=>"Name",				//企业名称
			"C_Legalper"=>"LegalPerson", 	//企业法人
			"C_Address"=>"Addr",			//企业地址
			"C_Tel"=>"Tel",					//企业联系电话
			"C_Fax"=>"Fax",					//企业传真
			"C_WWW"=>"Url",					//企业网址
			"C_Licence"=>"Licence",			//企业生产许可证
			"fm_Province"=>"Province",		//企业所在省
			"fm_City"=>"City",				//企业所在市
			"fm_County"=>"County",			//企业所在县
		];

		$sql = "select top $syncNum ".join(array_keys($map),",")." from $source where $source_idx > $p order by $source_idx asc";
		$result = $this->db_suyuan_multi->query($sql)->result_array();

		if(empty($result)){
			show_error("源数据库已全部同步完毕",500);
		}

		$data = [];
		foreach($result as $row){
			$tmp = [];
			foreach($map as $k=>$v){
				$tmp[$v]=$row[$k];
			}
			$this->on_map($tmp,$row);
			$data[] = $tmp;
		}

		$this->db->insert_batch($dest,$data);

		$row = $this->db->query("select top $syncNum max($dest_idx) as p from $dest")->row_array();
		if(!write_file(FCPATH."data/Ent.php",$row['p'])){
			show_error("无法保存同步进度",500,"系统发生了一个错误");
		}
		//$this->_afterSyncEnt($data);
	}

	/**
	 * 处理映射
	 * 处理目标数据表中的附加字段信息
	 * @param array $dest 		目标表每行的字段信息
	 * @param array $resouce 	源表每行的字段信息
	 */
	private function on_map(&$dest,$resouce){
		//更新目标表中每行的 政区域编码
		$dest["region"] = 0;
		$regions = $this->Region->getAll();
		foreach($regions as $region=>$county){
			if($county == $dest["County"]){
				$dest["region"] = $region;
				break;
			}
		}

		//更新目标表中每行的 经纬度信息
		$dest["Lng"] = 0;
		$dest["Lat"] = 0;
		$pos = $this->Gis->getLngLat($dest["Province"].$dest["City"].$dest["County"].$dest["Name"]);
		if($pos){
			$dest["Lng"] = $pos->lng;
			$dest["Lat"] = $pos->lat;
		}
	}

	/**
	 * @param array $data 新增数据
	 */
	private function _afterSyncEnt($data){

		$regions = $this->Region->getAll();
		foreach($regions as $region=>$county) {
			$this->db->where("Province", "山西省")
				->where("City", "运城市")
				->where("County", $county)
				->update("ProductEnt", ["Region" => $region]);
		}

		//更新经纬度坐标
		foreach($data as $row){
			$pos = $this->Gis->getLngLat($row["Province"].$row["City"].$row["County"].$row["Name"]);
			if($pos){
				$this->db->where("EntId",$row['EntId'])->update("ProductEnt",["Lng"=>$pos->lng,"Lat"=>$pos->lat]);
			}
		}

	}


	/**
	 * 同步企业批次信息
	 * @param int $EntId 企业ID
	 */
	public function SyncBat($EntId){
		$syncNum 	= 20; 					//每次同步20条
		$dest 		= "ProductBat";			//目标数据库
		$dest_idx	= "BatId";				//目标数据库与源数据关联字段
		$map 		= [						//源数据库 与 目标数据库 字段映射
			"CID"=>"CID",					//企业编号
			"PK_REPBID"=>"BatId",			//批次编号
			"REPB_Name"=>"BatName",			//批次名称
			"CPIID"=>"ProductId",			//产品ID
			"CPI_Name"=>"ProductName", 		//产品名称
			"CPI_Brand"=>"ProductBrand", 	//产品品牌
			"REPB_PDate"=>"ProductDateTime",//生产日期
			"REPB_Nums"=>"ProductNum",		//生产数量
		];

		//监管端数据库 查询数据当前企业最大批次ID
		$row = $this->AdmDB->query("select max($dest_idx) as p from $dest where EntId = ? group by EntId",[$EntId])->row_array();

		if(empty($row["p"])){
			$p = 0;
		}else{
			$p = $row["p"];
		}



		//获取监管端的企业信息
		$sql = "select top $syncNum   
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
        a.CPIID, --产品ID 非唯一
        b.CPI_Name, --产品名称
        b.CPI_Brand --产品品牌
		from SY_Rec_ProducBatches as a 
		join SY_Code_ProductInfo as b on b.PK_CPIID = a.CPIID 
		where a.CID = ? and a.PK_REPBID > $p
		order by a.PK_REPBID asc";


		try {
			$result = $this->setEnt($EntId)->query($sql, [$this->CID]);
			if(empty($result)){
				show_error("源数据库已全部同步完毕",500);
			}
		} catch (Exception $e) {
			show_error($e->getMessage());
		}

		//数据处理
		$data = [];
		$now = date("Y-m-d H:i:s");
		foreach($result as $row){
			$tmp = [];
			foreach($map as $k=>$v){
				$tmp[$v]=$row->$k;
			}
			//$this->on_batch_map($tmp,$row);
			//处理批次映射
			$tmp["EntId"] 			= $EntId;
			$tmp["SampleNum"] 		= $row->REPB_Nums;		//默认抽检数量 为批次数量
			$tmp["QualifiedNum"]	= $row->REPB_Nums;		//默认合格数量 全部合格
			$tmp["FailureNum"] 		= 0;					//默认不合格数量 0
			$tmp["QualifiedRate"] 	= 100;					//默认合格率 100%
			$tmp["InspectDateTime"]	= $now;					//检测时间

			$data[] = $tmp;
		}
		//监管端数据库 写入数据
		$this->AdmDB->insert_batch($dest,$data);
	}

	/**
	 * 同步企业产品信息
	 * @param $EntId
	 */
	public function SyncProduct($EntId){
		$syncNum 	= 20; 						//每次同步20条

		$source 	= "SY_Code_ProductInfo";	//源数据库
		$source_idx = "PK_CPIID";				//源数据库 中与目标数据库中关联的索引字段
		$dest 		= "ProductPrice";			//目标数据库
		$dest_idx 	= "ProductId";				//目标数据库 中与源数据库中关联的索引字段
		$map 		= [							//源数据库 与 目标数据库 字段映射
			"PK_CPIID"=>"ProductId",			//产品编号
			"CID"=>"CID",						//企业编号
			"CPI_Name"=>"ProductName", 			//产品名称
			"CPI_Barcodes"=>"ProductBarcode",	//产品条码
			"CPI_Brand"=>"ProductBrand",		//产品品牌
		];

		$row = $this->AdmDB->query("select max($dest_idx) as p from $dest where EntId = ? group by EntId",[$EntId])->row_array();
		if(empty($row["p"])){
			$p = 0;
		}else{
			$p = $row["p"];
		}

		try {
			$sql = "select top $syncNum " . join(array_keys($map), ",") . " from $source where CID = ? and $source_idx > $p order by $source_idx asc";
			$result = $this->setEnt($EntId)->query($sql, [$this->CID]);
		} catch (Exception $e) {
			show_error($e->getMessage(),500,"系统发生了一个错误");
		}

		if(empty($result)){
			show_error("源数据库已全部同步完毕",500,"系统发生了一个错误");
		}

		$data = [];
		foreach($result as $row){
			$tmp = [];
			foreach($map as $k=>$v){
				$tmp[$v]=$row->$k;
			}
			$tmp["EntId"] = $EntId;

			$data[] = $tmp;
		}
		//监管端数据库 写入数据
		$this->AdmDB->insert_batch($dest,$data);
	}

	/**
	 * 同步原料入库信息
	 * @param $EntId
	 */
	public function SyncRes($EntId){
		$syncNum 	= 20; //每次同步100条
		$source 	= "SY_Rec_Storage";		//源数据库
		$source_idx = "PK_RESID";			//源数据库 中与目标数据库中关联的索引字段
		$dest 		= "ProductRes";			//目标数据库
		$dest_idx 	= "ResId";				//目标数据库 中与源数据库中关联的索引字段
		$map 		= [						//源数据库 与 目标数据库 字段映射
			"PK_RESID"=>"ResId",			//原料编号
			"CID"=>"CID",					//真实企业编号
			"RES_Name"=>"ResName", 			//原料名称
			"CSID"=>"SupplierId",			//供应商编号
			"CS_Name"=>"SupplierName",		//供应商名称
		];

		//从监管端数据库 查询数据同步进度
		$row = $this->AdmDB->query("select max($dest_idx) as p from $dest where EntId = ? group by EntId",[$EntId])->row_array();
		if(empty($row["p"])){
			$p = 0;
		}else{
			$p = $row["p"];
		}

		try {
			$sql = "select top $syncNum 
			  a.PK_RESID,
			  a.CID,
			  a.RES_Name,
			  a.CSID,
			  b.CS_Name
			from $source as a,SY_Code_Supplier as b
			where a.CSID = b.PK_CSID and a.CID = b.CID and a.CID = ? and $source_idx > $p order by $source_idx asc";
			$result = $this->setEnt($EntId)->query($sql, [$this->CID]);
		} catch (Exception $e) {
			show_error($e->getMessage(),500,"系统发生了一个错误");
		}

		if(empty($result)){
			show_error("源数据库已全部同步完毕",500,"系统发生了一个错误");
		}

		$data = [];
		foreach($result as $row){
			$tmp = [];
			foreach($map as $k=>$v){
				$tmp[$v]=$row->$k;
			}
			$tmp["EntId"] = $EntId;

			$data[] = $tmp;
		}
		//监管端数据库 写入数据
		$this->AdmDB->insert_batch($dest,$data);
	}
	/**
	 * 设置企业ID
	 * @param int $EntId 监管系统企业ID
	 * @return $this
	 * @throws Exception
	 */
	public function setEnt($EntId){

		//从监管端数据库 获取企业信息
		$Ent		 	= $this->AdmDB->query("select * from productEnt where EntId =?",[$EntId])->row();

		if(!$Ent){
			throw new Exception("无法获取监管企业信息");
		}
		//连接企业端数据库
		$this->EntDB  	= $this->load->database($Ent->DBLink, true);

		if($EntId < 0 ){
			//溯源单平台 真实企业ID
			$this->CID = 1;
		}else{
			//溯源多平台 真实企业ID
			$this->CID = $EntId;
		}

		return $this;
	}

	/**
	 * 企业库 查询SQL
	 * @param string $sql
	 * @param bool $binds
	 * @param null $return_object
	 * @return
	 */
	public function query($sql, $binds = FALSE, $return_object = NULL){
		return $this->EntDB->query($sql, $binds, $return_object)->result();
	}
}