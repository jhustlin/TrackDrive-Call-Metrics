<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_phpthumb {
	
	public function __construct()
	{
		require_once 'phpthumb-master/ThumbLib.inc.php';
	}
	
	public function test()
	{
		$src_image = getcwd().'/files/upload/ea6dd3a6d197d9ccb64d8c7544b236a5.JPG';
		
		$thumb = PhpThumbFactory::create($src_image);
		$thumb->rotateImage('CW');
		
		// or:
		// $thumb->rotate('CCW');
		
		$thumb->adaptiveResize(175, 175);
		
		$thumb->show();
	}
	
	public function saveAdaptive($source_image, $target_image, $size)
	{
		$sizes = explode('x', $size);
		$thumb = PhpThumbFactory::create($source_image);
		$thumb->adaptiveResize($sizes[0], $sizes[1]);
		$thumb->save($target_image, 'jpg');
	}
	
	public function saveForFrame($source_image, $target_image, $size)
	{
		$sizes = explode('x', $size);
		$thumb = PhpThumbFactory::create($source_image);
		$thumb->resize($sizes[0], $sizes[1]);
		$thumb->save($target_image, 'jpg');
	}
}