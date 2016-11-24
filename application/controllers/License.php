<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SQLBuilder {
    public $table = "";
    public $fields = array();
    public $orderBy = "";
    public $join = array();
    public $where = array();

    function orderBy($str){
        $this->orderBy = $str;
        return $this;
    }

    function select($fields){
        $this->fields = $fields;
        return $this;
    }

    function from($table){
        $this->table = $table;
        return $this;
    }

    function where($condition){
        if(!empty($condition)){
            if(is_string($condition)){
                $arrCondition = [$condition];
            }
            if(is_array($condition)){
                $arrCondition = $condition;
            }
            foreach ($arrCondition as $v) {
                $this->where[] = $v;
            }
        }
        return $this;
    }

    function join($joinTable,$condition){
        $this->join[$joinTable] = $condition;
        return $this;
    }

    function buildCount(){
        $sql = "select count(*) as t from $this->table";
        $sql .= " where 1=1 ";
        foreach($this->where as $v){
            $sql .= "and $v ";
        }
        return $sql;
    }


    function buildBase(){
        $sql = "select ";
        foreach($this->fields as $field=>$desc){
            $sql .= $field ." , ";
        }
        $sql .= "ROW_NUMBER() OVER (order by $this->orderBy) as rn from $this->table";
        //foreach($this->join as $table=>$condition){
        //    $sql .= " join $table on $condition ";
        //}
        $sql .= " where 1=1 ";
        foreach($this->where as $v){
            $sql .= "and $v ";
        }
        return $sql;
    }


    //$sql = "select * from ( select *,ROW_NUMBER() OVER (order by start_date desc) as rn from $curTableName ) tt  where tt.rn<=$to and tt.rn>=$from";
    function buildSQL($from,$to){
        $sql = "select * from (";
        $sql .= $this->buildBase();
        $sql .= ") tt  where tt.rn<=$to and tt.rn>=$from\n";
        return $sql;
    }
}

/**
 * 配置类
 */
class C{
    public static $config = [
            "食品经营许可"=>[
                "table" => "formmain_1514",
                "fields" => [
                    "field0003"=>"证书类型",
                    "field0001"=>"企业名称",
                    "field0002"=>"许可证类型",
                    "field0014"=>"日常监管机构",
                    "field0004"=>"许可证号",
                    "field0008"=>"许可范围",
                    "field0006"=>"发证日期",
                    "field0007"=>"有效期至",
                ],
                "callback" => [
                    "field0003"=>"dict",
                    "field0002"=>"type",
                    "field0006"=>"date",
                    "field0007"=>"date",
                ],
                "where" => [
                    "field0002 like '%I%'"
                ],
                "condition"=>[
                    "licenseStartDate"=>"field0006 >= '?'",
                    "licenseFinalDate"=>"field0006 <= '?'",
                    "enterpriseName"=>"field0001 like '%?%'",
                ],
                "order_by"=>"start_date desc",
            ],
            "食品生产许可"=>[
                "table" => "formmain_1514",
                "fields" => [
                    "field0003"=>"证书类型",
                    "field0001"=>"企业名称",
                    "field0002"=>"许可证类型",
                    "field0014"=>"日常监管机构",
                    "field0004"=>"许可证号",
                    "field0008"=>"许可范围",
                    "field0006"=>"发证日期",
                    "field0007"=>"有效期至",
                ],
                "callback" => [
                    "field0003"=>"dict",
                    "field0002"=>"type",
                    "field0006"=>"date",
                    "field0007"=>"date",
                ],
                "where" => [
                    "field0002 like '%H%'"
                ],
                "condition"=>[
                    "licenseStartDate"=>"field0006 >= '?'",
                    "licenseFinalDate"=>"field0006 <= '?'",
                    "enterpriseName"=>"field0001 like '%?%'",
                ],
                "order_by"=>"start_date desc",
            ],
            "餐饮服务许可"=>[
                "table" => "formmain_1514",
                "fields" => [
                    "field0003"=>"证书类型",
                    "field0001"=>"企业名称",
                    "field0002"=>"许可证类型",
                    "field0014"=>"日常监管机构",
                    "field0004"=>"许可证号",
                    "field0008"=>"许可范围",
                    "field0006"=>"发证日期",
                    "field0007"=>"有效期至",
                ],
                "callback" => [
                    "field0003"=>"dict",
                    "field0002"=>"type",
                    "field0006"=>"date",
                    "field0007"=>"date",
                ],
                "where" => [
                    "field0002 like '%F%'"
                ],
                "condition"=>[
                    "licenseStartDate"=>"field0006 >= '?'",
                    "licenseFinalDate"=>"field0006 <= '?'",
                    "enterpriseName"=>"field0001 like '%?%'",
                ],
                "order_by"=>"start_date desc",
            ],
            "GSP认证"=>[
                "table" => "formmain_0324",
                "fields" => [
                    "field0003"=>"证书类型",
                    "field0001"=>"企业名称",
                    "field0002"=>"许可证类型",
                    "field0014"=>"日常监管机构",
                    "field0004"=>"许可证号",
                    "field0008"=>"许可范围",
                    "field0006"=>"发证日期",
                    "field0007"=>"有效期至",
                ],
                "callback" => [
                    "field0006"=>"date",
                    "field0007"=>"date",
                ],
                "condition"=>[
                    "licenseStartDate"=>"field0006 >= '?'",
                    "licenseFinalDate"=>"field0006 <= '?'",
                    "enterpriseName"=>"field0001 like '%?%'",
                ],
                "order_by"=>"start_date desc",
            ],
        ];

    public static function getTable($label){
        if(!empty(self::$config[$label]["table"])){
            return self::$config[$label]["table"];
        }
        return false;
    }

    public static function getFields($label){
        if(!empty(self::$config[$label]["fields"])){
            return self::$config[$label]["fields"];
        }
        return false;
    }

    public static function getWhere($label){
        if(!empty(self::$config[$label]["where"])){
            return self::$config[$label]["where"];
        }
        return false;
    }

    public static function getOrderBy($label){
        if(!empty(self::$config[$label]["order_by"])){
            return self::$config[$label]["order_by"];
        }
        return false;
    }

    public static function getCondition($label){
        if(!empty(self::$config[$label]["condition"])){
            return self::$config[$label]["condition"];
        }
        return false;
    }

    public static function getCallback($label){
        if(!empty(self::$config[$label]["callback"])){
            return self::$config[$label]["callback"];
        }
        return false;
    }
}


class License extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        //$this->load->config('seeyon',true);
        //$this->load->model('Seeyon');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
    }

    /**
     * 强制更新字典缓存
     */
    public function refresh(){
        $this->cache->delete("CTP_ENUM_ITEM");
        if (!$this->cache->get("CTP_ENUM_ITEM")){
            $result  = $this->db->query("select * from CTP_ENUM_ITEM")->result_array();
            $dict = array();
            foreach($result as $row){
                $dict[$row["ID"]]=$row["SHOWVALUE"];
            }
            $this->cache->save("CTP_ENUM_ITEM", $dict, 60*60*24*365*10);
        }
    }

    /**
     * 回调函数 获取字典
     * @param $value
     * @return mixed
     */
    private function callback_dict($value){
        if (!$dict = $this->cache->get("CTP_ENUM_ITEM")){
            $result  = $this->db->query("select * from CTP_ENUM_ITEM")->result_array();
            $dict = array();
            foreach($result as $row){
                $dict[$row["ID"]]=$row["SHOWVALUE"];
            }
            $this->cache->save("CTP_ENUM_ITEM", $dict, 60*60*24*365*10);
        }
        if(!empty($dict[$value])){
            return $dict[$value];
        }else{
            return $value;
        }
    }

    /**
     * 回调函数 格式化日期
     * @param $value
     * @return false|string
     */
    private function callback_date($value){
        return date("Y-m-d",strtotime($value));
    }

    /**
     * 回调函数 获取类型
     * @param $value
     * @return string
     */
    private function callback_type($value){
        if(strpos($value,"I")){
            return "食品经营许可";
        }
        if(strpos($value,"H")){
            return "食品生产许可";
        }
        if(strpos($value,"F")){
            return "餐饮服务许可";
        }
    }

    public function index()
    {
        $licenseTypes = [
            "食品经营许可"=>"食品经营许可",
            "食品生产许可"=>"食品生产许可",
            "餐饮服务许可"=>"餐饮服务许可",
            "GSP认证"=>"GSP认证",
        ];

        $licenseType                = $this->security->xss_clean($this->input->get_post('licenseType'));
        $pageNum                    = $this->security->xss_clean($this->input->get_post('per_page'));
        $licenseStartDate           = $this->security->xss_clean($this->input->get_post('licenseStartDate'));
        $licenseFinalDate           = $this->security->xss_clean($this->input->get_post('licenseFinalDate'));
        $enterpriseName             = $this->security->xss_clean($this->input->get_post('enterpriseName'));
        $search["licenseTypes"]     = $licenseTypes;
        $search["licenseType"]      = $licenseType;
        $search["licenseStartDate"] = $licenseStartDate;
        $search["licenseFinalDate"] = $licenseFinalDate;
        $search["enterpriseName"]   = $enterpriseName;



        if (empty($licenseType)) {
            $licenseType = "食品经营许可"; //默认食品经营许可
        }

        $condition = array();
        if (!empty($licenseStartDate) && !empty(C::getCondition($licenseType)["licenseStartDate"])){
            $condition[] = str_replace("?",$licenseStartDate,C::getCondition($licenseType)["licenseStartDate"]);
        }
        if (!empty($licenseFinalDate) && !empty(C::getCondition($licenseType)["licenseFinalDate"])){
            $condition[] = str_replace("?",$licenseFinalDate,C::getCondition($licenseType)["licenseFinalDate"]);
        }
        if (!empty($enterpriseName) && !empty(C::getCondition($licenseType)["enterpriseName"])){
            $condition[] = str_replace("?",$enterpriseName,C::getCondition($licenseType)["enterpriseName"]);
        }



        $curTableFields = C::getFields($licenseType);

        //获取记录总数
        $sqlBuilder = new SQLBuilder();
        $sql = $sqlBuilder->from(C::getTable($licenseType))->where(C::getWhere($licenseType))->where($condition)->buildCount();
        $result = $this->db->query($sql)->result_array();
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

        //$sql = "select * from ( select *,ROW_NUMBER() OVER (order by start_date desc) as rn from $curTableName ) tt  where tt.rn<=$to and tt.rn>=$from";

        $sqlBuilder = new SQLBuilder();
        $sqlBuilder->select(C::getFields($licenseType));
        $sqlBuilder->from(C::getTable($licenseType));
        $sqlBuilder->where(C::getWhere($licenseType));
        $sqlBuilder->where($condition);
        $sqlBuilder->orderBy(C::getOrderBy($licenseType));
        //$sqlBuilder->join("CTP_ENUM_ITEM b","b.id = $curTableName.field0003");
        $sql = $sqlBuilder->buildSQL($from,$to);
        $result = $this->db->query($sql)->result_array();

        foreach($result as &$row){
            foreach($row as $field=>&$value){
                foreach(C::getCallback($licenseType) as $field2=>$fn){
                    if($field == $field2){
                        $value = call_user_func_array([$this,"callback_".$fn],[$value]);
                    }
                }
            }
        }
        $config['base_url']             = site_url("license/index")."?licenseType=$licenseType";
        $config['uri_segment']          = 3;
        $config['total_rows']           = $total;
        $config['per_page']             = $pageSize;
        $config['enable_query_strings'] = true;
        $config['page_query_string']    = true;
        $config['use_page_numbers']     = TRUE;

        $config['first_link']   = '首页';
        $config['last_link']    = '尾页';
        $config['prev_link']    = '上一页';
        $config['next_link']    = '下一页';

        //把结果包在ul标签里
        $config['full_tag_open']    = '<div class="dataTables_paginate paging_simple_numbers">';
        $config['full_tag_close']   = '</div>';
        //首页
        $config['first_tag_open']   = '<span class="paginate_button">';
        $config['first_tag_close']  = '</span>';
        //尾页
        $config['last_tag_open']    = '<span class="paginate_button">';
        $config['last_tag_close']   = '</span>';
        //自定义数字
        $config['num_tag_open']     = '<span class="paginate_button">';
        $config['num_tag_close']    = '</span>';
        //当前页
        $config['cur_tag_open']     = '<span class="paginate_button current">';
        $config['cur_tag_close']    = '</span>';
        //前一页
        $config['prev_tag_open']    = '<span class="paginate_button previous">';
        $config['prev_tag_close']   = '</span>';
        //后一页
        $config['next_tag_open']    = '<span class="paginate_button next">';
        $config['next_tag_close']   = '</span>';

        $this->pagination->initialize($config);

        $page["total"]  = $total;
        $page["from"]   = $from;
        $page["to"]     = $to;
        $page["link"]   = $this->pagination->create_links();

        $this->load->view('license/index',compact("search","curTableFields","result","page"));
    }
}