<?php

class Structure extends CI_Model{
	
	var $structure_tab = 'structure';

	function __construct() {
		parent::__construct();
	}
	
	function get_list($language)
	{
		$result = array();
		$sql = 'SELECT
					*
				FROM
					`'.$this->db->dbprefix($this->structure_tab).'`
				WHERE
					`deleted` = "N"
					AND `language` = ?
				ORDER BY
					`position` ASC';
		$query = $this->db->query($sql, array($language));
		foreach ($query->result() as $row) if ($row->parent_id == 0) $result[$row->id] = $row;
		return $result;
	}

}