<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductTrace extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->db               = $this->load->database('default', true);
        $this->db_suyuan_multi  = $this->load->database('生产溯源多平台', true);
        $this->load->model("Gis");
		$this->load->model("Region");
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->library('Spyc');
        $this->load->library("phpqrcode");
        $this->load->library('pagination');
		$this->load->model("ReportRepository");
    }

    /**
     * 获得所有企业数据
     */
    public function GetAll(){
        $result = $this->db->query("select * from productTrace")->result_array();
        echo json_encode($result);
    }

    /**
     * 保存企业数据
     */
    public function Save(){
        $json   = $this->security->xss_clean($this->input->get_post('json'));
        $data   = json_decode($json);
        if(empty($data)){
            $output = ['status'=>0,'message'=>'Json数据不能空'];
            echo json_encode($output);
            return;
        }

        $failure = 0;
        foreach ($data as $row){
            $primaryKey = $row->ID;
            if(empty($row->ID)){
                unset($row->ID);
                if(!$this->db->insert('productTrace', $row)){
                    $failure ++;
                }
            }else {
                unset($row->ID);
                if (!$this->db->where("ID",$primaryKey)->update('productTrace', $row)) {
                    $failure ++;
                }
            }
        }
        $output = ['status'=>1,'message'=>"保存".count($data)."条记录，失败".$failure."条"];
        echo json_encode($output);
    }

    /**
     * 删除企业数据
     */
    public function Delete(){
        $json   = $this->security->xss_clean($this->input->get_post('json'));
        $data   = json_decode($json);
        if(empty($data) || $data=="null"){
            $output = ['status'=>0,'message'=>'Json数据不能空'];
            echo json_encode($output);
            return;
        }
        if($this->db->where_in("ID",$data)->delete('productTrace')){
            $output = ['status'=>1,'message'=>"成功删除".count($data)."条记录"];
        }else{
            $output = ['status'=>0,'message'=>"删除失败"];
        }
        echo json_encode($output);
    }


    /**
     * 按各县分组统计
     */
    public function County(){
        //$sql = "select County,count(*) as entNum from productTrace group by County";

        $sql = "
        select b.county, count(ID) as entNum from productTrace
        right join (
            select * from (VALUES('盐湖区'),('临猗县'),('万荣县'),('闻喜县'),('稷山县'),
            ('新绛县'),('绛县'),('垣曲县'),('夏县'),('平陆县'),('芮城县'),('永济市'),('河津市')) t(county)
        ) as b
        on productTrace.County = b.county
        group by b.county
        ";
        $result 	= $this->db->query($sql)->result();
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
    }

    /**
     * 按分类分组统计
     */
    public function Cate(){
        $sql = "select cate,count(*) as entNum from productTrace group by cate";
        $result = $this->db->query($sql)->result();
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
    }

    /**
     * 获得各行政区域下辖企业
     */
    public function Ent(){
		//$regions 	= $this->Region->getAll();
        $region     = $this->security->xss_clean($this->input->get_post('region'));

        if(empty($region)){
            show_error("行政区域不能为空",500,"系统发生了一个错误，原因如下：");
        }
        if(!$county=$this->Region->getRegionName($region)){
			show_error("行政区域不合法",500,"系统发生了一个错误，原因如下：");
		}


        $result = $this->db->select("id,entid,name,county,cate,lng,lat")->where("county",$county)->from("productEnt")->get()->result();
        
        foreach($result as &$row){
            $row->url = "<a href='".site_url("productTrace/product_batches")."/".$row->entid."' target='_blank'>监管</a>";
        }
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
    }

	/**
	 * 区域企业
	 * @param string $region
	 */
    public function region_enterprise($region=''){
		$regions = $this->Region->getAll();

        $config['first_link']   = '首页';
        $config['last_link']    = '尾页';
        $config['prev_link']    = '上一页';
        $config['next_link']    = '下一页';

        $config['full_tag_open'] = '<div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div><!--pagination-->';

        //$config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        //$config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        //$config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        //$config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $pageNum    = $this->security->xss_clean($this->input->get_post("per_page"));
        if (empty($region_id)){

            $result = $this->db->query("select count(*) as t from productEnt")->result_array();
            $total = 0;
            if (!empty($result[0]['t'])){
                $total = $result[0]['t'];
            }

            $pageSize       = 10;   //每页显示记录数
            $pageMax        = ceil($total/$pageSize);           //最大页码
            //页码过滤
            $pageNum        = (empty($pageNum))?1:$pageNum;     //当前页码
            $pageNum        = ($pageNum>$pageMax)?$pageMax:$pageNum;
            $pageNum        = ($pageNum<1)?1:$pageNum;
            $from           = ($pageNum-1) * $pageSize;
            $to             = $pageNum * $pageSize;
            $to             = ($to>$total)?$total:$to;

            $config['base_url']             = site_url(__CLASS__."/region_enterprise");
            $config['uri_segment']          = 3;
            $config['total_rows']           = $total;
            $config['per_page']             = $pageSize;
            $config['enable_query_strings'] = true;
            $config['page_query_string']    = true;
            $config['use_page_numbers']     = TRUE;

            $this->pagination->initialize($config);

            $page["total"]  = $total;
            $page["from"]   = $from+1;
            $page["to"]     = $to;
            $page["link"]   = $this->pagination->create_links();


            $sql = "select * from ( select *,ROW_NUMBER() OVER (order by ID desc) as rn from productEnt) tt  where tt.rn<=? and tt.rn>?";

            $result = $this->db->query($sql,[$to,$from])->result();
        }else{
			if(!$county = $this->Region->getRegionName($region)){
				show_error("行政区域不合法",500,"系统发生了一个错误，原因如下：");
			}

            $result = $this->db->query("select count(*) as t from productEnt where County=?",[$county])->result_array();
            $total = 0;
            if (!empty($result[0]['t'])){
                $total = $result[0]['t'];
            }

            $pageSize       = 10;   //每页显示记录数
            $pageMax        = ceil($total/$pageSize);           //最大页码
            //页码过滤
            $pageNum        = (empty($pageNum))?1:$pageNum;     //当前页码
            $pageNum        = ($pageNum>$pageMax)?$pageMax:$pageNum;
            $pageNum        = ($pageNum<1)?1:$pageNum;
            $from           = ($pageNum-1) * $pageSize;
            $to             = $pageNum * $pageSize;
            $to             = ($to>$total)?$total:$to;

            $config['base_url']             = site_url(__CLASS__."/region_enterprise/$region_id");
            $config['uri_segment']          = 4;
            $config['total_rows']           = $total;
            $config['per_page']             = $pageSize;
            $config['enable_query_strings'] = true;
            $config['page_query_string']    = true;
            $config['use_page_numbers']     = TRUE;

            $this->pagination->initialize($config);

            $page["total"]  = $total;
            $page["from"]   = $from+1;
            $page["to"]     = $to;
            $page["link"]   = $this->pagination->create_links();


            $sql = "select * from ( select *,ROW_NUMBER() OVER (order by ID desc) as rn from productEnt where County = ?) tt  where tt.rn<=? and tt.rn>?";

            $result = $this->db->query($sql,[$county,$to,$from])->result();
        }

        $this->load->view(__CLASS__."/region_enterprise",compact("result","page"));
    }

    /**
     * 生产批次
     * @param string $enterprise_id
     */
    public function product_batches($enterprise_id=""){
        if(empty($enterprise_id)){
            show_error("企业ID不能为空",500,"系统发生了一个错误，原因如下：");
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

        $pageNum    = $this->security->xss_clean($this->input->get_post('per_page'));

        $sql = <<<EOF
        select 
        count(a.PK_REPBID) as t
        from SY_Rec_ProducBatches as a
        --join SY_Rec_Rule as b on b.PK_RERID = a.RER_ID
        --join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID
        join SY_Rec_Products as b on b.REPBID = a.PK_REPBID --联合标签信息
        join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID --联合产品信息
        where a.CID = ?
EOF;
        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $result = $this->db_suyuan_single->query($sql,[1])->result_array();
        }else{
            $result = $this->db_suyuan_multi->query($sql,[$enterprise_id])->result_array();
        }
        $total = 0;
        if (!empty($result[0]['t'])){
            $total = $result[0]['t'];
        }

        $pageSize       = 20;   //每页显示记录数
        $pageMax        = ceil($total/$pageSize);           //最大页码
        //页码过滤
        $pageNum        = (empty($pageNum))?1:$pageNum;     //当前页码
        $pageNum        = ($pageNum>$pageMax)?$pageMax:$pageNum;
        $pageNum        = ($pageNum<1)?1:$pageNum;
        $from           = ($pageNum-1) * $pageSize;
        $to             = $pageNum * $pageSize;
        $to             = ($to>$total)?$total:$to;

        $config['base_url']             = site_url(__CLASS__."/product_batches/$enterprise_id");
        $config['uri_segment']          = 4;
        $config['total_rows']           = $total;
        $config['per_page']             = $pageSize;
        $config['enable_query_strings'] = true;
        $config['page_query_string']    = true;
        $config['use_page_numbers']     = TRUE;

        $config['first_link']   = '首页';
        $config['last_link']    = '尾页';
        $config['prev_link']    = '上一页';
        $config['next_link']    = '下一页';

        $config['full_tag_open'] = '<div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div><!--pagination-->';

        //$config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        //$config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        //$config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        //$config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page["total"]  = $total;
        $page["from"]   = $from + 1;
        $page["to"]     = $to;
        $page["link"]   = $this->pagination->create_links();

        //$sql = "select * from ( select *,ROW_NUMBER() OVER (order by start_date desc) as rn from $curTableName ) tt  where tt.rn<=$to and tt.rn>=$from";

        /*
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
        b.RER_Name, --规则名称
        c.CPI_Name, --产品名称
        ROW_NUMBER() OVER (order by PK_REPBID desc) as rn
        from SY_Rec_ProducBatches as a
        join SY_Rec_Rule as b on b.PK_RERID = a.RER_ID
        join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID
        where a.CID = ?
        ) tt  where tt.rn<=? and tt.rn>?
EOF;
*/
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
        b.REPS_Dan, --批次单号
        c.CPI_Name, --产品名称
        ROW_NUMBER() OVER (order by PK_REPBID desc) as rn 
        from SY_Rec_ProducBatches as a
        join SY_Rec_Products as b on b.REPBID = a.PK_REPBID --联合标签信息
        join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID --联合产品信息
        where a.CID = ?
        ) tt  where tt.rn<=? and tt.rn>?
EOF;

        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $result = $this->db_suyuan_single->query($sql,[1,$to,$from])->result();
        }else{
            $result = $this->db_suyuan_multi->query($sql,[$enterprise_id,$to,$from])->result();
        }

        //$result = $this->db_suyuan_multi->where("CID",$enterprise_id)->get("SY_Rec_ProducBatches")->result();
        $this->load->view(__CLASS__."/product_batches",compact("result","page","enterprise_id"));
    }

    /**
     * 批次详情
     * @param string $batch_id
     */
    public function batch_detail($enterprise_id="",$batch_id="",$qrcode=""){
        if(empty($batch_id)){
            show_error("批次ID不能为空",500,"系统发生了一个错误，原因如下：");
        }
        if(empty($enterprise_id)){
            show_error("企业ID不能为空",500,"系统发生了一个错误，原因如下：");
        }
        if(empty($qrcode)){
            show_error("二维码不能为空",500,"系统发生了一个错误，原因如下：");
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

        $sql = <<<EOF
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
        c.CPI_Name --产品名称
        from SY_Rec_ProducBatches as a
        join SY_Code_ProductInfo as c on c.PK_CPIID = a.CPIID
        where a.PK_REPBID = ? and a.CID = ?
EOF;

        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $batch_base = $this->db_suyuan_single->query($sql,[$batch_id,1])->result();
        }else{
            $batch_base = $this->db_suyuan_multi->query($sql,[$batch_id,$enterprise_id])->result();
        }

        $sql = <<<EOF
        SELECT
        PK_REPBPID, --流程ID
        REPBP_Name, --生产流程
        REPBM_Content, --流程描述
        REPBM_User, -- 操作人员
        REPBM_DateTime --操作时间
        FROM SY_Rec_ProBat_Procedures
        WHERE REPBID = ? AND CID = ?
EOF;

        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $batch_qc = $this->db_suyuan_single->query($sql,[$batch_id,1])->result();
        }else{
            $batch_qc = $this->db_suyuan_multi->query($sql,[$batch_id,$enterprise_id])->result();
        }



        $sql = <<<EOF
SELECT
SY_Rec_ProBat_Material.REPBM_Name, --原料
SY_Rec_ProBat_Material.REPBM_Value, --数量
SY_Rec_ProBat_Material.REPBM_Exp, --单位
SY_Rec_ProBat_Material.REPBID, --批次ID
SY_Rec_Storage.RES_User, --入库人
SY_Rec_Storage.RES_LDate, --入库时间
SY_Rec_Storage.RES_LDays, --保质天数
SY_Rec_Storage.RES_Name, --入库名称 
SY_Code_Supplier.CS_Name --供应商
FROM 
SY_Rec_ProducBatches 
INNER JOIN SY_Rec_ProBat_Material ON SY_Rec_ProBat_Material.REPBID = SY_Rec_ProducBatches.PK_REPBID 
INNER JOIN SY_Rec_Storage ON SY_Rec_ProBat_Material.RESIDS = SY_Rec_Storage.PK_RESID 
INNER JOIN SY_Code_Supplier ON SY_Rec_Storage.CSID = SY_Code_Supplier.PK_CSID 
WHERE SY_Rec_ProBat_Material.REPBID = ? and SY_Rec_ProBat_Material.CID = ?
EOF;
        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $batch_inbound = $this->db_suyuan_single->query($sql,[$batch_id,1])->result();
        }else{
            $batch_inbound = $this->db_suyuan_multi->query($sql,[$batch_id,$enterprise_id])->result();
        }


        $sql = <<<EOF
        select
        b.CD_Name, --经销商
        a.RESL_User, --出库人
        a.RESL_Date, --出库时间
        a.REPDan_Nums, --出库数量
        a.REPDan_Nums_All, --产品总数量
        a.REPDan_S, --扫码
        a.REPBID_S --批次串码
        from SY_Rec_SaleLog as a
        join SY_Code_Dealer as b on b.PK_CDID = a.CDID
        where a.REPBID_S like '%,??%' and a.CID = ?
EOF;
        $sql = str_replace("??",$batch_id,$sql);  //按批次查出厂记录

        /*
        $sql = <<<EOF
select
b.CD_Name, --经销商
a.RESL_User, --出库人
a.RESL_Date, --出库时间
a.REPDan_Nums, --出库数量
a.REPDan_Nums_All, --产品总数量
a.REPDan_S, --扫码
a.REPBID_S --批次串码
from SY_Rec_SaleLog as a
join SY_Code_Dealer as b on b.PK_CDID = a.CDID
where a.REPDan_S like '%,??%' and a.CID = ?
EOF;
        $sql = str_replace("??",$qrcode,$sql);      //按二维码查出厂记录
        */

        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $batch_outbound = $this->db_suyuan_single->query($sql,[1])->result();
        }else{
            $batch_outbound = $this->db_suyuan_multi->query($sql,[$enterprise_id])->result();
        }

        $sql = "SELECT * FROM SY_Rec_SaleLog2 WHERE REPBID = ? and CID = ?";
        $result  = $this->db_suyuan_multi->query($sql,[$batch_id,$enterprise_id])->result_array();
        $batch_circulate = $this->build_tree($result,"出库");


        /*
        $sql = "select top 1 REPS_Dan from SY_Rec_Products where REPBID = ? and CID = ?";

        if($enterprise_id<0){
            if(empty($single_db)){
                show_error("无法获取单平台数据库名称",500,"系统发生了一个错误，原因如下：");
            }
            $this->db_suyuan_single  = $this->load->database($single_db, true);
            $batch_qrcode = $this->db_suyuan_single->query($sql,[$batch_id,1])->row();
        }else{
            $batch_qrcode = $this->db_suyuan_multi->query($sql,[$batch_id,$enterprise_id])->row();
        }

        if(!empty($batch_qrcode->REPS_Dan)){
            $qrcode_url = "http://08.88721.com/bu.aspx?d=$batch_qrcode->REPS_Dan&m=";
            if(!file_exists(FCPATH."qrcode".DIRECTORY_SEPARATOR.md5($qrcode_url)."_L_10.png")){
                $qrcode_img = $this->qrcode($qrcode_url,md5($qrcode_url),"L",10);
            }else{
                $qrcode_img = md5($qrcode_url)."_L_10.png";
            }
        }else{
            $qrcode_img = "";
        }
        */

        //生成二维码
        $qrcode_url = "http://08.88721.com/bu.aspx?d=$qrcode&m=";
        if(!file_exists(FCPATH."qrcode".DIRECTORY_SEPARATOR.md5($qrcode_url)."_L_10.png")){
            $qrcode_img = $this->qrcode($qrcode_url,md5($qrcode_url),"L",10);
        }else{
            $qrcode_img = md5($qrcode_url)."_L_10.png";
        }

        $this->load->view(__CLASS__."/batch_detail",compact("batch_base","batch_qc","batch_inbound","batch_outbound","batch_circulate","qrcode_img"));
    }

    function findChild(&$arr,$id){
        $childs=array();
        foreach ($arr as $k => $v){
            if($v['FromUser']== $id){
                $childs[]=$v;
            }
        }
        return $childs;
    }
    function build_tree($result,$root_id){
        $childs = $this->findChild($result,$root_id);
        if(empty($childs)){
            return null;
        }
        foreach ($childs as $k => $v){
            $rescurTree = $this->build_tree($result,$v["ToUser"]);
            if( null != $rescurTree){
                $childs[$k]['childs']=$rescurTree;
            }
        }
        return $childs;
    }

    private function qrcode($data,$fileName,$errorCorrectionLevel='L',$matrixPointSize=10){
        // 二维码数据
        //$data = 'http://gz.altmi.com';
        // 纠错级别：L、M、Q、H
        //$errorCorrectionLevel = 'L';
        // 点的大小：1到10
        //$matrixPointSize = 10;
        // 生成的文件名
        $filePath = FCPATH."qrcode".DIRECTORY_SEPARATOR.$fileName.'_'.$errorCorrectionLevel.'_'.$matrixPointSize.'.png';
        QRcode::png($data, $filePath, $errorCorrectionLevel, $matrixPointSize, 2);
        if(file_exists($filePath))
            return basename($filePath);
        else
            return FALSE;
    }


    public function index(){
		$data["batNumTop"] 		= $this->ReportRepository->countBatNumGroupByEnt();		//企业批次数量 Top5
		$data["productNumTop"] 	= $this->ReportRepository->countProductNumGroupByEnt();	//企业产品数量 Top5
        //$data['productEntNum'] = $this->ReportRepository->countEntNum();					//统计上线企业数量
        $this->load->view("ProductTrace/index",$data);
    }

    public function getLngLat($enterpriseId){
        $row = $this->db->where("EnterpriseId",$enterpriseId)->get("productTrace")->row();
        $data["enterprise"] = $row;
        $pos = $this->Gis->getLngLat("山西省运城市".$row->County.$row->Name);
        if($pos){
            if($this->db->where("EnterpriseId",$enterpriseId)->update("productTrace",["lng"=>$pos->lng,"lat"=>$pos->lat])){
                echo "update enterprise lnglat success!";
            }else{
                echo "update enterprise lnglat failure!";
            }


        }else{
            echo "can't get enterprise lnglat";
        }
        $data["pos"] = $pos;

    }

    /*


    public function Json(){
        $sql        = $this->security->xss_clean($this->input->get_post('sql'));
        if(empty($sql)){
            show_error("sql不能为空",500,"系统发生了一个错误，原因如下：");
        }
        $filters    = ["insert","update","delete","drop"];
        foreach($filters as $filter){
            if (strpos(strtolower($sql),$filter)!==false){
                show_error("sql包含非法字符",500,"系统发生了一个错误，原因如下：");
            }
        }
        $result = $this->db_muti->query($sql)->result();
        echo json_encode($result);
    }

    public function Yaml(){
        $sql        = $this->security->xss_clean($this->input->get_post('sql'));
        if(empty($sql)){
            show_error("sql不能为空",500,"系统发生了一个错误，原因如下：");
        }
        $filters    = ["insert","update","delete","drop"];
        foreach($filters as $filter){
            if (strpos(strtolower($sql),$filter)!==false){
                show_error("sql包含非法字符",500,"系统发生了一个错误，原因如下：");
            }
        }
        $result = $this->db_muti->query($sql)->result_array();
        echo Spyc::YAMLDump($result);
    }


    public function Table(){
        $sql        = $this->security->xss_clean($this->input->get_post('sql'));
        if(empty($sql)){
            show_error("sql不能为空",500,"系统发生了一个错误，原因如下：");
        }
        $filters    = ["insert","update","delete","drop"];
        foreach($filters as $filter){
            if (strpos(strtolower($sql),$filter)!==false){
                show_error("sql包含非法字符",500,"系统发生了一个错误，原因如下：");
            }
        }
        $result = $this->db_muti->query($sql)->result_array();

        echo "<table>";

        $i = 0;
        foreach($result as $row){
            $i++;
            echo "<tr>";
            foreach($row as $k=>$v){
                echo "<th>$k</th>";
            }
            echo "</tr>";
            if($i==1){
                break;
            }
        }
        foreach($result as $row){
            echo "<tr>";
            foreach($row as $k=>$v){
               echo "<td>$v</td>";
            }
            echo "</tr>";
        }
        echo "</table>";

    }
    */
}