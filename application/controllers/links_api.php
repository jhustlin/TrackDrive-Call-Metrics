<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Links_api extends MX_Controller {
	
    public function __construct() {
            parent::__construct();
            // nocache
            $this->gclass->nocache();
    }

    public function index($traffic_source_id = null, $type = 'landing') {
        if($traffic_source_id != null) {
            // load models
            $this->load->model('Links');
            $link = $this->Links->getLanding();
            exit($link->link.'/'.$traffic_source_id);
        }
        
        exit('Nothing found!');
    }
	
}