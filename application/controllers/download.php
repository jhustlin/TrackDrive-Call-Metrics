<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// nocache
		$this->gclass->nocache();
	}
	
	public function index($mod = '', $path = '', $file = '')
	{
		$path = str_replace(array("/", "\\", ".."), '', $path);
		$file = str_replace(array("/", "\\", ".."), '', $file);
		if ($path != '' && $file != '' && file_exists(DOCROOT.FILES_DIR.$path.'/'.$file)) {
			// load helper
			$this->load->helper('download');
			// read file
			$data = file_get_contents(DOCROOT.FILES_DIR.$path.'/'.$file);
			force_download($file, $data);
		}
	}
	
}