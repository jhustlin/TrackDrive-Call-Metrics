<?php
/**
 * created: 2014-03 ()
 * author: Ģirts Kļaviņš (klavins.girts@gmail.com)
 * info: admin translations controller
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_translations extends MX_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// nocache
		$this->gclass->nocache();
		// check
		if ($this->session->userdata('admin_user') === FALSE) redirect(base_url('admin'), 'refresh');
		if (!in_array('translations', explode(',', $this->session->userdata('admin_user')->access))) redirect(base_url('admin'), 'refresh');
		// load models
		$this->load->model('Translations');
		// set language
		setTranslationLanguage($this->session->userdata('admin_user')->sys_lang, 'admin');
	}
	
	public function translate()
	{
		echo $this->Translations->translate($this->input->post('id'), $this->input->post('translation'));
		die();
	}
	
	public function index()
	{
		// type
		if ($this->session->userdata('translate_type') === FALSE || !in_array($this->session->userdata('translate_type'), array('admin'))) $this->session->set_userdata('translate_type', 'admin');
		// change type
		if (in_array($this->input->get('change_type'), array('admin')))
		{
			$this->session->set_userdata('translate_type', $this->input->get('change_type'));
			// redirect
			redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
		}
		// delete
		if ($this->input->get('del') > 0 && $this->session->userdata('developer') == 'Y')
		{
			if ($this->Translations->deleteKeyword($this->input->get('del')) > 0)
			{
				// redirect
				redirect(base_url('admin/translations'), 'refresh');
			}
		}
		$data = array();
		// get translations
		$data['translations'] = $this->Translations->getTranslations($this->session->userdata('translate_type'), $this->session->userdata('content_language'));
		// create empty translate records
		$inact = 0;
		foreach (unserialize(ARB_LANGUAGES) as $language => $title) 
		{
			foreach ($data['translations'] as $keyword => $tr_data)
			{
				if (!isset($tr_data[$language])) {
					if ($this->Translations->insertEmpty($keyword, $language, $this->session->userdata('translate_type')) > 0) $inact++;
				}
			}
		}
		// redirect
		if ($inact > 0) redirect(base_url('admin/translations'), 'refresh');

		// set title
		$this->gclass->addTitle(__('Translations'));
		
		// meta
		$meta = array(
			'title' => implode(' - ', array_reverse($this->gclass->title_array)),
			'keywords' => implode(', ', $this->gclass->keywords_array),
			'description' => implode(', ', $this->gclass->description_array),
			'css' => $this->gclass->css_array,
			'js' => $this->gclass->js_array
		);
		$this->load->view('header', $meta);
		$this->load->view('admin_translations', $data);
		$this->load->view('footer_iframe');
	}

}