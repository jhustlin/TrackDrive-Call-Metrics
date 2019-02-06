<?php

class File_list extends MX_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data = $files = $all_files = array();
		
		if ($this->input->get('type') == 'all' || $this->input->get('type') == 'img')
		{
			foreach (explode(',', ALL_DIRECTORIES) as $fdir)
			{
				$dir = opendir(DOCROOT.FILES_DIR.$fdir);
				while ($item = readdir($dir)) if (!is_dir(DOCROOT.FILES_DIR.$item))
				{
					$curr_filename = DOCROOT.FILES_DIR.$fdir.'/'.$item;
					$file_info = array(
						'name' => $item,
						'size' => format_filesize(filesize($curr_filename)),
						'modif' => filemtime($curr_filename)
					);
					$files[$fdir][] = $file_info;
					$all_files[] = array('title' => $item, 'value' => WEBROOT.FILES_DIR.$fdir.'/'.$item);
				}
				closedir($dir);
				$data['directories'] = explode(',', ALL_DIRECTORIES);
				$data['files'] = $files;
			}
		} else {
			$data[] = array('title' => 'My page 1', 'value' => 'http://www.tinymce.com');
			$data[] = array('title' => 'My page 2', 'value' => 'http://www.google.com');
		}
		if ($this->input->get('type') == 'img') $data = $all_files;
		// out
		echo json_encode($data);
	}

}
?>