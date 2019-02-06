<?php
/**
 * created: 2014-03 ()
 * author: Ģirts Kļaviņš (klavins.girts@gmail.com)
 * info: admin settings controller
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_sys_users extends MX_Controller {
	var $user_list = array();
	var $modules_list = array();
	
	public function __construct()
	{
		parent::__construct();
		// nocache
		$this->gclass->nocache();
		// check
		if ($this->session->userdata('admin_user') === FALSE) redirect(base_url('admin'), 'refresh');
		if (!in_array('sys_users', explode(',', $this->session->userdata('admin_user')->access))) redirect(base_url('admin'), 'refresh');
		// load models
		$this->load->model('Sys_users');
		// get user list
		$this->user_list = $this->Sys_users->get_list();
		// set language
		setTranslationLanguage($this->session->userdata('admin_user')->sys_lang, 'admin');
	}
	
	public function index($user_id = -1)
	{
		// set title
		$this->gclass->addTitle(__('System users'));
		// data
		$data = array();
		$data['user_list'] = $this->user_list;
		// get user
		if ($user_id >= 0) {
			if (isset($data['user_list'][$user_id]))
			{
				// set title
				$this->gclass->addTitle(__('Edit user'));
				// data
				$data['user'] = $data['user_list'][$user_id];
				// set title
				$this->gclass->addTitle($data['user']->name_surname);
			} else {
				// set title
				$this->gclass->addTitle(__('Create new user'));
				// delete and create new
				$this->Sys_users->delete_temporary();
				$tmp_id = $this->Sys_users->create_temporary();
				$data['user'] = $this->Sys_users->get($tmp_id);
			}
		}
		
		// add Js
		$this->gclass->addJs('http://code.jquery.com/jquery-migrate-1.2.1.min.js');
		$this->gclass->addJs('/js/admin/jquery.uniform.min.js');
		$this->gclass->addJs('/js/admin/jquery.form.min.js');
		
		// add Css
		$this->gclass->addCss('/css/admin/uniform.default.css');
		
		// meta
		$meta = array(
			'title' => implode(' - ', array_reverse($this->gclass->title_array)),
			'keywords' => implode(', ', $this->gclass->keywords_array),
			'description' => implode(', ', $this->gclass->description_array),
			'css' => $this->gclass->css_array,
			'js' => $this->gclass->js_array
		);
		$this->load->view('header', $meta);
		$this->load->view('admin_sys_users', $data);
		$this->load->view('footer_iframe');
	}
	
	public function update($user_id)
	{
		if ($this->session->userdata('admin_user')->id == $user_id) $_POST['type'] = 'admin';
		
		if (isset($this->user_list[$user_id]))
		{
			foreach ($_POST as $key => $value) if (!is_array($value)) $_POST['key'] = trim($value);
			// data
			$data = array();
			$data['status'] = 'er';
			// validate
			if ($_POST['email'] == '') $data['error']['email'] = 'Please enter E-mail address!';
			if ($this->user_list[$user_id]->temporary == 'Y' && $_POST['password'] == '') $data['error']['password'] = 'Please enter Password!';
			// update
			if (!isset($data['error'])) {
				// get user access
				if (!isset($_POST['module']) || $_POST['type'] == 'admin' && !in_array('sys_users', $_POST['module'])) $_POST['module'][] = 'sys_users';
				if ($_POST['type'] != 'admin' && in_array('sys_users', $_POST['module'])) unset($_POST['module'][array_search('sys_users', $_POST['module'])]);
				$access = isset($_POST['module']) && count($_POST['module']) > 0 ? implode(',', $_POST['module']) : '';
				// set params
				$params = array(
					'id' => $user_id,
					'name_surname' => $_POST['name_surname'],
					'email' => $_POST['email'],
					'access' => $access,
                    'traffic_source' => $_POST['traffic_source'],
                    'company_subdomain' => $_POST['company_subdomain'],
					'sys_lang' => $_POST['sys_lang']
				);
				if (isset($_POST['type']) && $_POST['type'] != '') $params['type'] = $_POST['type'];
				if (isset($_POST['password']) && $_POST['password'] != '') $params['password'] = $_POST['password'];
				// run
				$this->Sys_users->update($params);
				// alert
				if ($this->db->affected_rows() > 0) $this->session->set_flashdata('alert', array('type' => 'ok', 'msg' => $this->user_list[$user_id]->temporary == 'Y' ? __('New createded!') : __('Data was updated!')));
				// for out
				if ($_POST['close'] == 1) {
					$data['run'][1] = 'parent.refreshIFrame();';
				} else {
					if ($this->user_list[$user_id]->temporary == 'Y') {
						$data['run'][1] = "$('#iframe_sys_users', parent.document).attr('src', '/admin/sys_users/".$user_id."');";
					} else {
						// status
						$data['status'] = 'ok';
					}
				}
				// if self
				if ($this->session->userdata('admin_user')->id == $user_id)
				{
					// update session
					$user = $this->Sys_users->get($user_id);
					$user->content_lang = $this->session->userdata('admin_user')->content_lang;
					$this->session->set_userdata('admin_user', $user);
					// ch
					$data['status'] = 'er';
					$data['run'][1] = 'parent.document.location.href = parent.document.location.href;';
				}
				
			}
			// out
			echo json_encode($data);
		}
	}
	public function delete($user_id)
	{
		if ($user_id > 0 && isset($this->user_list[$user_id]))
		{
			$this->Sys_users->delete($user_id);
			// alert
			$this->session->set_flashdata('alert', array('type' => 'ok', 'msg' => __('Deleted!')));
			// redirect
			redirect('/admin/sys_users', 'location');
		}
	}
	
	
}