<?php

class Sys_users extends CI_Model {

	var $users_tab = 'sys_users';

	function __construct() {
		parent::__construct();
	}
	
	function get_list()
	{
		$result = array();
		$sql = 'SELECT
					*
				FROM
					`'.$this->db->dbprefix($this->users_tab).'`
				WHERE
						`deleted` = "N"
						'.($this->session->userdata('admin_user')->type == 'admin' ? '' : ' AND id = '.intval($this->session->userdata('admin_user')->id)).'
				ORDER BY
						`type` DESC, `id` ASC';
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) $result[$row->id] = $row;
		return $result;
	}
	
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
	
	function create_temporary()
	{
		$sql = 'INSERT INTO
							`'.$this->db->dbprefix($this->users_tab).'`
						SET
							`created` = NOW()';
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	function delete_temporary()
	{
		$sql = 'DELETE FROM
							'.$this->db->dbprefix($this->users_tab).'
						WHERE
							`temporary` = "Y"';
		$this->db->query($sql);
	}
	
	function get($user_id) {
		$sql = 'SELECT
					*
				FROM
					`'.$this->db->dbprefix($this->users_tab).'`
				WHERE
					`deleted` = "N"
					AND `id` = ?';
		$query = $this->db->query($sql, array($user_id));
		return $query->row();
	}
	
	function update($par)
	{
		$sql = 'UPDATE
					`'.$this->db->dbprefix($this->users_tab).'`
				SET
					`temporary` = "N",
					`name_surname` = ?,
					`email` = ?,
					`access` = ?,
                    `traffic_source` = ?,
                    `company_subdomain` = ?,
					`sys_lang` = ?
					'.(isset($par['type']) ? ',`type` = ?': '').'
					'.(isset($par['password']) ? ',`password` = MD5(?)': '').'
				WHERE
					`id` = ?';
		$params = array(
			$par['name_surname'],
			$par['email'],
			$par['access'],
            $par['traffic_source'],
            $par['company_subdomain'],
			$par['sys_lang']
		);
		if (isset($par['type'])) $params['type'] = $par['type'];
		if (isset($par['password'])) $params['password'] = $par['password'];
		$params['id'] = $par['id'];
		$this->db->query($sql, $params);
	}
	
	function delete($user_id)
	{
		$sql = 'UPDATE
					`'.$this->db->dbprefix($this->users_tab).'`
				SET
					`deleted` = "Y"
				WHERE
					`id` = ?';
		$this->db->query($sql, array($user_id));
	}
	
}
