<?php

/**
 * Class region
 * 行政区域类
 */
class Region extends CI_Model
{
	protected $regions;
	public function __construct()
	{
		parent::__construct();
		$this->init();
	}
	private function init(){
		$regions =[
			"140800"=>"运城市",
			"140801"=>"市辖区",
			"140802"=>"盐湖区",
			"140821"=>"临猗县",
			"140822"=>"万荣县",
			"140823"=>"闻喜县",
			"140824"=>"稷山县",
			"140825"=>"新绛县",
			"140826"=>"绛县",
			"140827"=>"垣曲县",
			"140828"=>"夏县",
			"140829"=>"平陆县",
			"140830"=>"芮城县",
			"140881"=>"永济市",
			"140882"=>"河津市",
		];
		$this->regions = $regions;
	}

	/**
	 * 获取所有行政区域
	 * @return mixed
	 */
	public function getAll(){
		return $this->regions;
	}

	/**
	 * 获取根据ID 获取某一行政区域名称
	 * @param string $region 编号或名称
	 * @return null
	 */
	public function getRegionName($region){
		if(in_array($region,array_keys($this->regions))){
			return $this->regions[$region];
		}elseif(in_array($region,array_values($this->regions))){
			return $region;
		}else{
			return false;
		}
	}
}