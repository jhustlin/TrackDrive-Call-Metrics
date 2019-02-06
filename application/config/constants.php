<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$tmp = array(
                'en' => 'english',
            );
define('ARB_LANGUAGES', serialize($tmp));

define('ALL_MODULES', 'sys_users,translations,admin_calls,links');
//define('ALL_MODULES', 'structure,categories,posters,news,news1,pagination,simple,banners,gallery,options_values,users,file_manager,settings,sys_users,translations');

define('ALL_DIRECTORIES', 'photo,video,banners,gallery,upload,projects,documents');

define('WEBROOT', '/');
define('DOCROOT', $_SERVER['DOCUMENT_ROOT'].'/');

// files
define('FILES_DIR', 'files/');
define('UPLOAD_DIR', FILES_DIR.'upload/');
define('BANNER_DIR', FILES_DIR.'banners/');
define('GALLERY_DIR', FILES_DIR.'gallery/');
define('PHOTO_DIR', FILES_DIR.'photo/');
define('VIDEO_DIR', FILES_DIR.'video/');
define('PROJETS_DIR', FILES_DIR.'projects/');
define('DOCUMENTS_DIR', FILES_DIR.'documents/');
define('TRACKDRIVE_API', 'VCyoexYsLxsDW3sezB_D');


/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */