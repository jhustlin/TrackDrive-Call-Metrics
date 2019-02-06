<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// nocache
		$this->gclass->nocache();
		// check
		if ($this->session->userdata('language') == '' && $this->input->post('language') == '') die();
		// set language
		if ($this->input->post('language') != '') {
			setTranslationLanguage($this->input->post('language'), 'public');
		} else {
			setTranslationLanguage($this->session->userdata('language'), 'public');
		}
	}
	
	public function register()
	{
		// load model
		$this->load->model('Users');
		// trim
		$_POST = array_map('trim', $_POST);
		// data
		$error = array();
		// validate fieds	
		if ($this->input->post('email') == '') $error['error']['email'] = __('Please enter Email');
		if (!isset($error['error']['email']))
		{
			if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST['email'])) 
				$error['error']['email'] = __('Invalid E-mail');
		}
		// check for existing email
		if (!isset($error['error']['email']))
		{
			$user = $this->Users->get_by_email($this->input->post('email'));
			if (isset($user->id)) $error['error']['email'] = __('This email already exists');
		}
		if ($this->input->post('name') == '') $error['error']['name'] = _('Please enter Name');
		if ($this->input->post('surname') == '') $error['error']['surname'] = _('Please enter Surname');
		if ($this->input->post('password') == '') $error['error']['password'] = __('Please enter Password');
		if (!isset($error['error']['password']) && mb_strlen($_POST['password'], 'UTF-8') < 5) $error['error']['password'] = __('at least 5 characters');
		if ($this->input->post('password2') == '') $error['error']['password2'] = __('Please enter Password');
		if (!isset($error['error']['password2']) && $_POST['password'] != $_POST['password2']) $error['error']['password2'] = __('Passwords do not match, please retype');
		// register
		if (!isset($error['error']))
		{
			$activation_key = md5(time());
			// create user
			$this->Users->reg_create(
				$this->input->post('email'),
				$this->input->post('name'),
				$this->input->post('surname'),
				$this->input->post('password'),
				$activation_key
			);
			// send email
			$url = 'http://wcms.lv/'.$this->session->userdata('language').($this->session->userdata('language') == 'en' ? '/register' : '/register').'?activation_key='.$activation_key;
			$message = 'Hi<br />
			for activation:
			<a href="'.$url.'">'.$url.'</a><br />
			Info:<br />
			E-mail: '.$_POST['email'].'<br />
			Password: '.trim($_POST['password']).'<br />';
			sendMail($this->input->post('email'), 'Activation', $message);
			// info
			$error['status'] = 'er';
			$error['run'][1] = 'document.location.href="/'.$this->session->userdata('language').'/register?done"';
		}
		// out
		echo json_encode($error);
	}

	public function forgot()
	{
		// load model
		$this->load->model('Users');
		// trim
		$_POST = array_map('trim', $_POST);
		// data
		$error = array();
		if ($this->input->post('email') == '') $error['error']['email'] = __('Please enter Email');
		if (!isset($error['error']))
		{
			// search for user
			$user = $this->Users->get_by_email($this->input->post('email'));
			if (isset($user->id))
			{
				$activation_key = md5(time());
				// update user
				$this->Users->set_activation_key($user->id, $activation_key);
				// send mail
				$url = 'http://wcms.lv/'.$this->session->userdata('language').($this->session->userdata('language') == 'en' ? '/forgot' : '/forgot').'?forgot_key='.$activation_key;
				$message = 'To change password:<br />
				<a href="'.$url.'">'.$url.'</a>';
				sendMail($this->input->post('email'), 'Change password', $message);
				// info
				$error['status'] = 'er';
				$error['run'][1] = 'document.location.href="/'.$this->session->userdata('language').'/forgot?done"';
			} else {
				$error['error']['email'] = __('E-mail not found!');
			}
		}
		// out
		echo json_encode($error);
	}
	
	public function log_in()
	{
		// load model
		$this->load->model('Users');
		// trim
		$_POST = array_map('trim', $_POST);
		// data
		$error = array();
		if ($this->input->post('email') == '') $error['error']['email'] = __('Please enter Email');
		if ($this->input->post('password') == '') $error['error']['password'] = __('Please enter Password');
		if (!isset($error['error'])) {
			// search for user
			$user = $this->Users->get_by_form($this->input->post('email'), $this->input->post('password'));
			if (isset($user->id))
			{
				// set session
				$this->session->set_userdata('user', $user);
				// out
				$error['status'] = 'er';
				$error['run'][1] = 'location.reload(true);';
			} else {
				$error['error']['email'] = __('User not found!');
			}
		}
		// out
		echo json_encode($error);
	}

	public function newsletter()
	{
		// load model
		$this->load->model('Newsletter');
		// trim
		$post = array_map('trim', $this->input->post());
		// data
		$data = array();
		$data['status'] = 'er';
		
		// validate fieds
		if (isset($post['email']) && trim($post['email']) == '') $data['info'] = __('Please enter Email');	
		if (!isset($data['info'])) {
			if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $post['email'])) 
				$data['info'] = __('Invalid E-mail');
		}
		
		// check for exsist
		if (!isset($data['info'])) {
			if ($this->Newsletter->get_email($post['email']) > 0) $data['info'] = __('This email already registered!');
		}
			
		// save
		if (!isset($data['info'])) {
			$this->Newsletter->add_email(array(
				$post['email'],
				$this->input->post('language'),
				$_SERVER['REMOTE_ADDR']
			));
			//
			$data['info'] = __('You have signed up for newsletter!');
			$data['status'] = 'ok';
		}
				
		// out
		echo json_encode($data);
	}
	
}