<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uploadify_v3 extends MX_Controller {

	public $view_data = array();
	private $upload_config;

	function __construct() {
		parent::__construct();
	}

	public function do_upload() {


		$this->load -> library('upload');

		$upload_dir = !in_array($this->input->post('force_dir'), explode(',', ALL_DIRECTORIES)) ? UPLOAD_DIR : $this->input->post('force_dir');

		$this->upload_config = array(
			'upload_path' => DOCROOT.FILES_DIR.$upload_dir,
			'allowed_types' => 'png|jpg|jpeg|bmp|tiff',
			'max_size' => 1024 * 6,
			'remove_space' => TRUE,
			'encrypt_name' => isset($_POST['encrypt_name']) && $_POST['encrypt_name'] == 0 ? FALSE : TRUE
		);
		
		

		$this -> upload -> initialize($this -> upload_config);

		if (!$this -> upload -> do_upload("userfile")) {
			$upload_error = $this -> upload -> display_errors();
			echo json_encode($upload_error);
		} else {
			$file_info = $this -> upload -> data();
			echo json_encode($file_info);
		}

	}

	public function do_gallery_upload() {
		// load libaries
		$this->load->library('My_phpthumb');
		$this->load -> library('upload');

		$upload_dir = !in_array($this->input->post('force_dir'), explode(',', ALL_DIRECTORIES)) ? UPLOAD_DIR : $this->input->post('force_dir');

		$this->upload_config = array(
			'upload_path' => DOCROOT.FILES_DIR.$upload_dir,
			'allowed_types' => 'png|jpg|jpeg|bmp|tiff',
			'max_size' => 1024 * 6,
			'remove_space' => TRUE,
			'encrypt_name' => isset($_POST['encrypt_name']) && $_POST['encrypt_name'] == 0 ? FALSE : TRUE
		);
		
		$this -> upload -> initialize($this -> upload_config);
		
		if (!$this -> upload -> do_upload("userfile")) {
			$upload_error = $this -> upload -> display_errors();
			echo json_encode($upload_error);
		} else {
			$file_info = $this->upload->data();
			// create small image
			$this->my_phpthumb->saveAdaptive($file_info['full_path'], DOCROOT.FILES_DIR.$upload_dir.'/'.'thumb_'.$file_info['file_name'], '227x189');
			// create big image
			$this->my_phpthumb->saveForFrame($file_info['full_path'], DOCROOT.FILES_DIR.$upload_dir.'/'.$file_info['file_name'], '1024x768');
			// out
			echo json_encode($file_info);
		}

	}

}
