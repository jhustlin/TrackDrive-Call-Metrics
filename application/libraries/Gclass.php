<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Gclass {

	public $title_array = array();
	public $keywords_array = array();
	public $description_array = array();
	public $css_array = array();
	public $js_array = array();
	
	public $translation_array = array();
	
	public $cms_version = '1.0.1';
	public $cms_languages = array(
		'lv' => 'latviešu',
		'en' => 'english',
		'ru' => 'русский',
		//'lt' => 'lietuvos',
		//'sp' => 'spaanju'
	);

	public function __construct()
	{
		//parent::__construct();
		// default
            
            if (preg_match('/dev/',$_SERVER['HTTP_HOST'])) {
		$CI =& get_instance();
                $this->base = 'http://trackdrive.dev/';
                $this->favicon = '<link rel="icon" type="image/gif" href="/images/admin/favicon.gif" />';
                $this->title_array[] = 'GSG';
                $this->keywords_array = array('def', 'def2');
                $this->description_array = array('gsg', 'cms');
                $this->css_array = array(
                        'http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css',
                        '/css/reset.css',
                        //'http://fonts.googleapis.com/css?family=Muli:400,400italic',
                        '/js/fancybox/source/jquery.fancybox.css?v=2.1.5',
                        '/css/font-awesome.min.css',
                        '/css/admin/style.css'
                );
                $this->js_array = array(
                        'http://code.jquery.com/jquery-1.10.1.min.js',
                        'http://code.jquery.com/ui/1.10.4/jquery-ui.js',
                        '/js/admin/jquery.form.min.js',
                        '/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5',
                        '/js/admin/script.js'
                );
            } else {
                $CI =& get_instance();
                $this->base = 'http://techrevenue.net/';
                $this->favicon = '<link rel="icon" type="image/gif" href="/images/admin/favicon.gif" />';
                $this->title_array[] = 'GSG';
                $this->keywords_array = array('def', 'def2');
                $this->description_array = array('gsg', 'cms');
                $this->css_array = array(
                        'http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css',
                        '/css/reset.css',
                        '/css/font-awesome.min.css',
                        //'http://fonts.googleapis.com/css?family=Muli:400,400italic',
                        '/js/fancybox/source/jquery.fancybox.css?v=2.1.5',
                        '/css/admin/style.css'
                );
                $this->js_array = array(
                        'http://code.jquery.com/jquery-1.10.1.min.js',
                        'http://code.jquery.com/ui/1.10.4/jquery-ui.js',
                        '/js/admin/jquery.form.min.js',
                        '/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5',
                        '/js/admin/script.js'
                );
            }
	}
	
	function addTitle($text) {
		$this->title_array[] = $text;
	}
	function addKeyword($text) {
		$this->keywords_array[] = $text;
	}
	function addDescription($text) {
		$this->description_array[] = $text;
	}
	function addCss($text) {
		$this->css_array[] = $text;
	}
	function addJs($text) {
		$this->js_array[] = $text;
	}





	public function addMeta($data_arr)
	{
		$css = '';
		if (count($this->css_array) > 0) foreach ($this->css_array as $css_item) $css .= "\n\t".'<link href="'.$css_item.'" rel="stylesheet" type="text/css" />';
		$js = '';
		if (count($this->js_array) > 0) foreach ($this->js_array as $js_item) $js .= "\n\t".'<script type="text/javascript" src="'.$js_item.'"></script>';
		
		$CI =& get_instance();
		$res = $CI->db->query('SELECT site_url FROM '.$CI->db->dbprefix('settings'))->row();

		$meta_array = array(
			'site_url' => $res->site_url,
			'page_title' => implode(' - ', array_reverse($this->title_array)),
			'page_keywords' => count($this->keywords_array) > 0 ? "\n\t".'<meta name="keywords" content="'.implode(', ', $this->keywords_array).'" />' : '',
			'page_description' => count($this->description_array) > 0 ? "\n\t".'<meta name="description" content="'.implode(', ', $this->description_array).'" />' : '',
			'page_css' => $css,
			'page_js' => $js
		);
		return array_merge($data_arr, $meta_array);
	}
	

	// get translation
	public function __($keyword = '' )
	{

	}

	public function nocache()
	{
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", FALSE);
		header("Pragma: no-cache");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	}

}
	/* End of file Someclass.php */