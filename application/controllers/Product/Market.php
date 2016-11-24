<?php
class Market extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("MarketRepository");
	}

	public function json()
	{
		$result 	= $this->MarketRepository->getAllEntMarketRate();
		$callback   = $this->security->xss_clean($this->input->get_post('callback'));
		if(empty($callback)){
			echo json_encode($result);
		}else{
			echo $callback."(".json_encode($result).")";
		}
	}
}