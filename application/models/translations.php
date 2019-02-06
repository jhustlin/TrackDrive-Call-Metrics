<?php

class Translations extends CI_Model {

	var $translations_tab = 'translations';

	function __construct() {
		parent::__construct();
	}
	
	function getTranslations($translate_type, $content_language)
	{
		$res = array();
		$sql = 'SELECT
					*
				FROM
					`'.$this->db->dbprefix($this->translations_tab).'`
				WHERE
					`type` = ?
					AND `language` = ?';
		$query = $this->db->query($sql, array(
			$this->session->userdata('translate_type'),
			$this->session->userdata('content_language')
		));
		foreach ($query->result() as $row)
		{
			$res[$row->keyword][$row->language] = array(
				'id' => $row->id,
				'translation' => $row->translation
			);
		}
		return $res;
	}
	
	function insertEmpty($keyword, $language, $translate_type)
	{
		$sql = 'INSERT IGNORE INTO
								'.$this->db->dbprefix($this->translations_tab).'
							SET
								`keyword` = ?,
								`language` = ?,
								`type` = ?';
		$this->db->query($sql, array(
			$keyword,
			$language,
			$translate_type
		));
		return $this->db->affected_rows();
	}
	
	function translate($id, $translation)
	{
		// update
		$sql = 'UPDATE
					`'.$this->db->dbprefix($this->translations_tab).'`
				SET
					`translation` = ?
				WHERE
					`id` = ?';
		$this->db->query($sql, array($translation, $id));
		// get
		$sql = 'SELECT
					`translation`
				FROM
					`'.$this->db->dbprefix($this->translations_tab).'`
				WHERE
					`id` = ?';
		$query = $this->db->query($sql, array($id));
		$res = $query->row();
		return $res->translation;
	}
	
	function deleteKeyword($id)
	{
		// get keyword
		$sql = 'SELECT
					`keyword`,
					`type`
				FROM
					`'.$this->db->dbprefix($this->translations_tab).'`
				WHERE
					`id` = ?';
		$query = $this->db->query($sql, array($id));
		$res = $query->row();
		// delete records
		$sql = 'DELETE FROM
					`'.$this->db->dbprefix($this->translations_tab).'`
				WHERE
					`keyword` = ?
					AND `type` = ?';
		$this->db->query($sql, array($res->keyword, $res->type));
		return $this->db->affected_rows();
	}
}