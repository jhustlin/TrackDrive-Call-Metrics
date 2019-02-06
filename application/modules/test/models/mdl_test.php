<?php

class Mdl_test extends CI_Model {

	var $structure_tab = 'gsg_structure';
	
	function __construct() {
		parent::__construct();
	}
	
	function get()
	{
		$res = array();
		
		$sql = 'SELECT * FROM '.$this->structure_tab;
		$query = $this->db->query($sql);
		return $query->row();
	}
}