<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = 'admin';


$route['image'] = 'admin_posters/image';
$route['uploadify_v3'] = 'uploadify_v3';

//api
$route['getLink/(:any)'] = 'links_api/index/$1';

// admin
$route['admin/file_list'] = 'file_list';
$route['admin/admin_calls'] = 'admin_calls';

$route['admin/links/(:num)'] = 'admin_links/index/$1';
$route['admin/links/add'] = 'admin_links/index/0';
$route['admin/links/position'] = 'admin_links/position';
$route['admin/links/(:any)/(:num)'] = 'admin_links/$1/$2';
$route['admin/links'] = 'admin_links';

$route['admin'] = 'admin';
$route['admin/ajax_login'] = 'admin/ajax_login';
$route['admin/logout'] = 'admin/logout';
$route['admin/ajax_content_language'] = 'admin/ajax_content_language';
$route['admin/ajax_translations/(:any)'] = 'admin/ajax_translations/$1';

$route['admin/settings'] = 'admin_settings';
$route['admin/settings/update'] = 'admin_settings/update';

$route['admin/translations'] = 'admin_translations';
$route['admin/translations/translate'] = 'admin_translations/translate';

$route['admin/sys_users/(:num)'] = 'admin_sys_users/index/$1';
$route['admin/sys_users/add'] = 'admin_sys_users/index/0';
$route['admin/sys_users/(:any)/(:num)'] = 'admin_sys_users/$1/$2';
$route['admin/sys_users'] = 'admin_sys_users';

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */