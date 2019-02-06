<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Menu extends MX_Controller {
	public function __construct()
	{
		parent::__construct();
		// load models
		$this->load->model('Structure');
	}
		
	public function index()
	{
		
		$data['list'] = $this->Structure->get_list('lv');
		
		$this->load->view('menu', $data);
	}
}