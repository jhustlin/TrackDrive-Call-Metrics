<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends MX_Controller {

	public function __construct() {
		parent::__construct();
		// nocache
		$this->gclass->nocache();
		
		$this->load->model('mdl_test');
	}
	
	public function index()
	{
		// get
		$aaa = $this->mdl_test->get();
		// show
		echo '<div>'.$aaa->item_key.'</div>';
	}
}
