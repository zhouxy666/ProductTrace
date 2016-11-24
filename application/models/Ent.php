<?php

class Ent extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database('default', true);
	}

	/**
	 * 获取所有企业 数据集
	 * @return mixed
	 */
	public function getList(){
		return $this->db->get("productEnt")->result();
	}

	/**
	 * 获取所有企业 树形数据集
	 * @return array
	 */
	public function getTree(){
		$result = $this->db->query("select * from productEnt order by region")->result_array();
		$tree 	= [];
		foreach($result as $row){
			$tree[$row["Region"]]["Name"]									= $row["County"];
			$tree[$row["Region"]]["Enterprises"][$row["EntId"]]["EntId"]	= $row["EntId"];
			$tree[$row["Region"]]["Enterprises"][$row["EntId"]]["Name"]		= $row["Name"];
		}
		return $tree;
	}

	/**
	 * 获取指定企业
	 * @param int $entId 企业ID
	 * @return array
	 */
	public function getById($entId){
		return $this->db->where("EntId",$entId)->get("productEnt")->row();
	}

}