<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed' );

/*
$this->load->library('image_lib');
$this->load->library('iclass');
		
$source_image = '';
$new_image = '';

// save resized
$image_size = '300x';
if ($this->iclass->saveResized($source_image, $new_image, $image_size))
{
	echo 'ok';
}

// save adaptive
$image_size = '350x150';
if ($this->iclass->saveAdaptive($source_image, $new_image, $image_size))
{
	echo 'ok';
}
*/
class Iclass extends CI_Image_lib {
	
	function GSG_Image_lib() {
		parent::CI_Image_lib();
	}
	
	function saveResized($source_image, $new_image, $image_size)
	{
		// get sizes
		$s = explode('x', $image_size);
		$width = $s[0] == '' || $s[0] < 0 ? 1 : $s[0];
		$height = $s[1] == '' || $s[1] < 0 ? 1 : $s[1];
		// check
		if (file_exists($source_image) && file_exists(substr($new_image, 0, strrpos($new_image, '/'))) && ($s[0] != '' || $s[1] != ''))
		{
			// config
			$config['image_library'] = 'gd2';
			$config['source_image']	= $source_image;
			$config['new_image'] = $new_image;
			$config['quality'] = 80;
			$config['maintain_ratio'] = TRUE;
			$config['master_dim'] = $width >= $height ? 'width' : 'height';
			$config['width'] = $width;
			$config['height'] = $height;
			// initialize
			$this->initialize($config);
			// force convert to jpg
			$this->convert('jpg');
			// resize
			if (!$this->resize())
			{
				echo $this->display_errors();
			} else {
				return true;
			}
		}
		return false;
	}
	
	function saveAdaptive($source_image, $new_image, $image_size)
	{
		// get sizes
		$s = explode('x', $image_size);
		$width = $s[0];
		$height = $s[1];
		$source_vals = @getimagesize($source_image);
		// check
		if (file_exists($source_image) && file_exists(substr($new_image, 0, strrpos($new_image, '/'))) && $s[0] != '' && $s[1] != '')
		{
			// config
			$config['image_library'] = 'gd2';
			$config['source_image']	= $source_image;
			$config['new_image'] = $new_image;
			$config['quality'] = 80;
			$config['maintain_ratio'] = TRUE;
			$config['width'] = $width;
			$config['height'] = $height;
			// master_dim
			$config['master_dim'] = $source_vals[0] <= $source_vals[1] ? 'width' : 'height';
			if ($source_vals[0] <= $source_vals[1] && $width > $height) $config['master_dim'] = 'width';
			if ($source_vals[0] <= $source_vals[1] && $width < $height) $config['master_dim'] = 'height';
			if ($source_vals[0] > $source_vals[1] && $width > $height) $config['master_dim'] = 'width';
			if ($source_vals[0] > $source_vals[1] && $width < $height) $config['master_dim'] = 'height';
			// initialize
			$this->initialize($config);
			// force convert to jpg
			$this->convert('jpg');
			// resize
			if (!$this->resize())
			{
				echo $this->display_errors();
			} else {
				// config
				$config['image_library'] = 'gd2';
				$config['source_image']	= $new_image;
				$config['maintain_ratio'] = FALSE;
				$config['width'] = $width;
				$config['height'] = $height;
				// set axis
				if ($source_vals[0] <= $source_vals[1])
				{
					$config['y_axis'] = $this->height / 2 - $height / 2;
				} else {
					$config['x_axis'] = $this->width / 2 - $width / 2;
				}
				if ($source_vals[0] <= $source_vals[1] && $width > $height) $config['y_axis'] = $this->height / 2 - $height / 2;
				if ($source_vals[0] <= $source_vals[1] && $width < $height) $config['x_axis'] = $this->width / 2 - $width / 2;
				if ($source_vals[0] > $source_vals[1] && $width > $height) $config['y_axis'] = $this->height / 2 - $height / 2;
				if ($source_vals[0] > $source_vals[1] && $width < $height) $config['x_axis'] = $this->width / 2 - $width / 2;
				// initialize
				$this->initialize($config);
				if (!$this->crop())
				{
					echo $this->display_errors();
				} else {
					return true;
				}
			}
		}
		return false;
	}
	
	function convert($type = 'jpg', $delete_orig = TRUE) {
		$this -> full_dst_path = $this -> dest_folder . end($this -> explode_name($this -> dest_image)) . '.' . $type;

		if (!($src_img = $this -> image_create_gd())) {
			return FALSE;
		}

		if ($this -> image_library == 'gd2' AND function_exists('imagecreatetruecolor')) {
			$create = 'imagecreatetruecolor';
		} else {
			$create = 'imagecreate';
		}
		$copy = 'imagecopy';

		$props = $this -> get_image_properties($this -> full_src_path, TRUE);
		$dst_img = $create($props['width'], $props['height']);
		$copy($dst_img, $src_img, 0, 0, 0, 0, $props['width'], $props['height']);

		$types = array('gif' => 1, 'jpg' => 2, 'jpeg' => 2, 'png' => 3);

		$this -> image_type = $types[$type];

		if ($delete_orig) {
			//unlink($this -> full_src_path);
			$this -> full_src_path = $this -> full_dst_path;
		}

		if ($this -> dynamic_output == TRUE) {
			$this -> image_display_gd($dst_img);
		} else {
			if (!$this -> image_save_gd($dst_img)) {
				return FALSE;
			}
		}

		imagedestroy($dst_img);
		imagedestroy($src_img);

		@chmod($this -> full_dst_path, DIR_WRITE_MODE);

		return TRUE;
	}
	
	public function convert1($file_name)
	{
		echo $file_name;
	}
}

/* End of file Iclass.php */