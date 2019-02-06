<?php
/**
 * created: 2014-03 ()
 * author: Ģirts Kļaviņš (klavins.girts@gmail.com)
 * info: admin login/logout controller
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// nocache
		$this->gclass->nocache();
		// load model
		$this->load->model('Sys_users');
		// set language
		setTranslationLanguage($this->session->userdata('admin_user') !== FALSE ? $this->session->userdata('admin_user')->sys_lang : 'en', 'admin');
	}
	
	public function index()
	{
		// set deveploper
		if ($this->input->get('dev') !== FALSE)
		{
			$this->session->set_userdata('developer', $this->session->userdata('developer') != 'Y' ? 'Y' : 'N');
			// redirect
			redirect(base_url('admin'), 'refresh');
		}
		// cookie login
		if ($this->input->cookie('admin') != '' && $this->session->userdata('admin_user') === FALSE)
		{
			// explode cookie
			$cookie = explode('|', $_COOKIE['admin']);
			// get user
			$user = $this->Sys_users->get_user_from_cookie($cookie[0], $cookie[1]);;
			if (isset($user->id))
			{
				// create user session
				$user->content_lang = 'en'; // default content language
				$this->session->set_userdata('admin_user', $user);
			} else {
				delete_cookie('admin');
			}
			// redirect
			redirect($this->uri->uri_string(), 'location');
		}
		
		// view
		if ($this->session->userdata('admin_user') !== FALSE)
		{
			$data = array(
				'user_data' => $this->session->userdata('admin_user')
			);
			// meta
			$meta = array(
				'title' => implode(' - ', array_reverse($this->gclass->title_array)),
				'keywords' => implode(', ', $this->gclass->keywords_array),
				'description' => implode(', ', $this->gclass->description_array),
				'css' => $this->gclass->css_array,
				'js' => $this->gclass->js_array
			);
			$this->load->view('header', $meta);
			$this->load->view('admin_index', $data);
			$this->load->view('footer');
		} else {
			// set title
			$this->gclass->addTitle(__('Login'));
			// meta
			$meta = array(
				'title' => implode(' - ', array_reverse($this->gclass->title_array)),
				'keywords' => implode(', ', $this->gclass->keywords_array),
				'description' => implode(', ', $this->gclass->description_array),
				'css' => $this->gclass->css_array,
				'js' => $this->gclass->js_array
			);
			$this->load->view('header', $meta);
			$this->load->view('admin_login', array());
			$this->load->view('footer');
		}
	}
	
	public function home()
	{
		
		$user_data = $this->session->userdata('admin_user');
		
		$data = array(
			'user_data' => $user_data
		);
		
		// meta
		$meta = array(
			'title' => implode(' - ', array_reverse($this->gclass->title_array)),
			'keywords' => implode(', ', $this->gclass->keywords_array),
			'description' => implode(', ', $this->gclass->description_array),
			'css' => $this->gclass->css_array,
			'js' => $this->gclass->js_array
		);
		
		$this->gclass->addJs('/js/admin/jquery.ui.datepicker-'. $this->session->userdata('admin_user')->sys_lang.'.js');
		
		$this->load->view('header', $meta);
		$this->load->view('admin_home', $data);
		$this->load->view('footer');
	}
	// login
	public function ajax_login()
	{
		$data = array();
		$data['status'] = 'er';
		// check
		if ($this->input->post('email') == '') $data['error']['email'] = 'er';
		if ($this->input->post('password') == '') $data['error']['password'] = 'er';
		// act
		if (!isset($data['error'])) {
			// form login
			$user = $this->Sys_users->get_user($this->input->post('username'), $this->input->post('password'));
			if (isset($user->id))
			{
				// unset existing user session
				$this->session->unset_userdata('admin_user');
				// create user session
				$user->content_lang = 'en'; // default content language
				$this->session->set_userdata('admin_user', $user);
				// if set cookie
				$cookie_code = '';
				if ($this->input->post('remember_me') == 'Y')
				{
					$cookie_code = rand(1000, 9999999);
					// set cookie
					$cookie = array(
						'name'		=> 'admin',
						'value'		=> $user->id.'|'.$cookie_code,
						'expire'	=> time() + 60 * 60 * 24 * 366,
						'path'		=> '/',
						'secure'	=> FALSE
					);
					$this->input->set_cookie($cookie);
				}
				// update user
				$this->Sys_users->update_user_login($cookie_code, $user->id);
				// change status
				$data['status'] = 'ok';
			} else {
				$data['run'][1] = "$('#info_block').html('".__('Wrong username and/or password')."');";
				$data['run'][2] = "$('#info_block').show();";
			}
		}
		// reload page
		if ($data['status'] == 'ok') $data['run'][1] = "document.location.href = document.location.href;";
		// out
		echo json_encode($data);
	}
	
	// logout
	public function logout()
	{
		$this->session->unset_userdata('admin_user');
		$this->session->unset_userdata('content_language');
		delete_cookie('admin');
		// redirect
		redirect('/admin', 'refresh');
	}
	
	// javascript translations
	public function ajax_translations()
	{
		//setTranslationLanguage(in_array($this->uri->segment(3), array('lv', 'ru', 'en')) ? $this->uri->segment(3) : 'lv', 'admin'); // default language
		// out
		echo "language['ok'] = '".__('Ok')."';";
		echo "language['cancel'] = '".__('Cancel')."';";
		echo "language['Alert'] = '".__('Alert')."';";
		echo "language['Field cannot be empty!'] = '".__('Field cannot be empty!')."';";
		echo "language['Too short'] = '".__('Too short')."';";
		echo "language['Weak'] = '".__('Weak')."';";
		echo "language['Good'] = '".__('Good')."';";
		echo "language['Strong'] = '".__('Strong')."';";
	}
	
	// set content languade
	public function ajax_content_language()
	{
		$status = 'error';
		// set content language
		if ($this->input->post('language') != '' && in_array($this->input->post('language'), array('lv', 'ru', 'en')) && $this->session->userdata('admin_user') !== FALSE)
		{
			$user_data = $this->session->userdata('admin_user');
			$user_data->content_lang = $this->input->post('language');
			$this->session->set_userdata('admin_user', $user_data);
			$status = 'ok';
		}
		echo json_encode($status);
	}
	
}