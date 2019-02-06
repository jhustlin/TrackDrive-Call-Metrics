<?php

class Settings extends CI_Model {

	var $settings_tab = 'settings';

	function __construct() {
		parent::__construct();
	}
	
	function getAll($language)
	{
		$sql = 'SELECT
						*,
						`'.substr($language, 0, 2).'_site_name` as site_name,
						`'.substr($language, 0, 2).'_keywords` as keywords,
						`'.substr($language, 0 ,2).'_description` as description
					FROM
						`'.$this->db->dbprefix($this->settings_tab).'`';
		return $this->db->query($sql)->row();
	}

	function update($language)
	{
		$dta = array(
			substr($language, 0, 2).'_site_name' => $this->input->post('site_name'),
			'site_url' => $this->input->post('site_url'),
			substr($language, 0, 2).'_keywords' => $this->input->post('keywords'),
			substr($language, 0, 2).'_description' => $this->input->post('description'),
			'email' => $this->input->post('email'),
			'block_tp' => $this->input->post('block_tp') == 'Y' ? 'Y' : 'N',
			'block_ip' => $this->input->post('block_ip'),
			'block_txt' => $this->input->post('block_txt'),
			'lang_lv' => $this->input->post('lang_lv') == 'Y' ? 'Y' : 'N',
			'lang_ru' => $this->input->post('lang_ru') == 'Y' ? 'Y' : 'N',
			'lang_en' => $this->input->post('lang_en') == 'Y' ? 'Y' : 'N'
		);
		$this->db->update($this->db->dbprefix($this->settings_tab), $dta, array('id' => 1));
	}





/*

	function get_user($email, $password)
	{
		$sql = 'SELECT
					*,
					NULL as password
				FROM
					`'.$this->db->dbprefix($this->users_tab).'`
				WHERE
					`deleted` = "N"
					AND `email` = ?
					AND `password` = MD5(?)';
		$query = $this->db->query($sql, array(
			$email,
			$password
		));
		return $query->row();
	}
	
	function get_user_from_cookie($user_id, $cookie_code)
	{
		$sql = 'SELECT
					*,
					NULL as password
				FROM
					`'.$this->db->dbprefix($this->users_tab).'`
				WHERE
					`deleted` = "N"
					AND `id` = ?
					AND `cookie_code` = ?';
		$query = $this->db->query($sql, array(
			$user_id,
			$cookie_code
		));
		return $query->row();
	}
	
	function update_user_login($cookie_code, $user_id)
	{
		$sql = 'UPDATE
					`'.$this->db->dbprefix($this->users_tab).'`
				SET
					`cookie_code` = ?,
					`last_login` = NOW()
				WHERE
					`id` = ?';
		$this->db->query($sql, array($cookie_code, $user_id));
	}
*/
}
