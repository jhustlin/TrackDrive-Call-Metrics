<?php
// set translation language
function setTranslationLanguage($language = '', $type = 'public' )
{
	$CI =& get_instance();
	$CI->load->library('gclass');
	define('TRANSLATION_TYPE', $type);
	define('TRANSLATION_LANGUAGE', $language);
	//$CI =& get_instance();
	$sql = 'SELECT
				`keyword`,
				`translation`
			FROM
				`'.$CI->db->dbprefix('translations').'`
			WHERE
				`language` = ?
				AND `type` = ?';
	$query = $CI->db->query($sql, array($language, $type));
	foreach ($query->result() as $row) $CI->gclass->translation_array[$row->keyword] = $row->translation;
}


function __($keyword = '')
{
	$CI =& get_instance();
	$CI->load->library('gclass');
	$keyword = preg_replace('/[^ (\x20-\x7F)]*/', '', trim($keyword)); // remove Non ASCII
	// translation
	if ($keyword != '' && defined('TRANSLATION_LANGUAGE'))
	{
		if (array_key_exists($keyword, $CI->gclass->translation_array))
		{
			return $CI->gclass->translation_array[$keyword] == '' ? $keyword.'->'.TRANSLATION_LANGUAGE : $CI->gclass->translation_array[$keyword];
		}
		else
		{
			// save new
			$sql = 'INSERT IGNORE INTO
						`'.$CI->db->dbprefix('translations').'`
					SET
						`keyword` = ?,
						`translation` = ?,
						`language` = ?,
						`type` = ?';
			$CI->db->query($sql, array(
				$keyword,
				'',
				TRANSLATION_LANGUAGE,
				TRANSLATION_TYPE
			));
			return $keyword.'->'.TRANSLATION_LANGUAGE;
		}
	}
}

function getAllSections()
{
	return array(
		'content' => __('Content'),
		'elements' => __('Elements'),
		'special' => __('Special'),
		'tools' => __('Tools'),
		'parts' => __('Parts'),
		'stock' => __('Stock'),
		'work' => __('Work'),
		'system' => __('System')
	);
}

function getValidModules($access = array(), $all = FALSE)
{
	$modules = array();
	$controllers_dir = getcwd().'/application/controllers/admin/';
	$handle = opendir($controllers_dir);
	while (($df = readdir($handle)) !== false)
	{
		if (is_file($controllers_dir.$df) && $df != 'main.php')
		{
			// read module header (6 rows)
			$header = file($controllers_dir.$df);
			$title = $file = $section = $position = '';
			if (isset($header[2])) $title = trim(str_replace('* title:', '', $header[2]));
			if (isset($header[3])) $assoc = substr(trim(str_replace('* file:', '', $header[3])), 0, -4);;
			if (isset($header[4])) $section = trim(str_replace('* section:', '', $header[4]));
			if (isset($header[5])) $position = intval(str_replace('* position:', '', $header[5]));
			if ($title != '' && $assoc != '' && $section != '' && $position > 0 && ((count($access) > 0 && in_array($assoc, $access)) || $all))
			{
				$modules[$section][$position] = array(
										'title' => $title,
										'assoc' => $assoc
										);
			}
		}
	}
	return $modules;
}

// display alert
function displayAlert($alert) {
	$CI =& get_instance();
	if (!isset($alert->type) || !in_array($alert->type, array('ok', 'er'))) $alert->type = 'ok';
	if ($CI->session->userdata('admin_user')->notices == 'Y')
	{
	?>
		<script type="text/javascript">
			$(function() {
				var text = '<div id="notification_<?php echo $alert->type ?>">\
								<img src="/admin_files/images/atention.png" height="30" />\
								<span style="margin-left: 10px;"><?php echo $alert->message ?></span>\
							</div>';
				notification_(text);
			});
		</script>
	<?php
	}
	// unset session
	$CI->session->unset_userdata('alert');
}
// set alert
function setAlert($msg, $type = 'ok')
{
	$CI =& get_instance();
	$CI->session->set_userdata('alert', (object) array(
			'message' => $msg,
			'type' => $type
		)
	);
}

function outH($unescaped)
{
	return htmlspecialchars($unescaped);
	//$CI =& get_instance();
	//return $CI->db->escape_str($unescaped);
}

function format_filesize($bytes, $decimals = 2)
{
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function eng2lat($date){
	return ($date!='' && $date!='0000-00-00 00:00:00') ? date('d.m.Y H:i:s',strtotime($date)) : '-';
}
function eng2lat_ys($date){
	return ($date!='' && $date!='0000-00-00 00:00:00') ? date('d.m.y H:i:s',strtotime($date)) : '-';
}

function eng2lat_s($date){
	return ($date!='' && $date!='0000-00-00') ? date('d.m.Y',strtotime($date)) : '';
}

function convert_date($date_time = '', $format = 'd.m.Y H:i:s', $null = false) {
	if ($null && $date_time == '') return '';
	if ($date_time == '') $date_time = time();
	if (!is_numeric($date_time)) $date_time = strtotime(str_replace(array('/'), array('-'), $date_time));
	return date($format, $date_time);
}

function subval_sort_object( $a, $subkey, $order = 'asc') {
	foreach( $a as $k=>$v ) $b[$k] = strtolower( $v[$subkey] );
	if( $order === 'desc' )
		arsort( $b );
	else
		asort( $b );
	foreach( $b as $k=>$v ) $c[] = (object) $a[$k];
	return $c;
}

function eur2c($eur = 0) {
	return str_replace('.', '', number_format(str_replace(',', '.', trim($eur)), 2, '.', ''));
}

function c2eur($c, $null = 'null') {
	if ($c <= 0 && $null != 'null') return $null;
	return number_format(round($c / 100, 2), 2, '.', '');
}

function sendMail($email, $subject, $message)
{
	$headers = "From: server@server.lv\r\n" .
	       'X-Mailer: PHP/' . phpversion() . "\r\n" .
	       "MIME-Version: 1.0\r\n" .
	       "Content-Type: text/html; charset=utf-8\r\n" .
	       "Content-Transfer-Encoding: 8bit\r\n\r\n";
	
	mail($email, '=?utf-8?B?'.base64_encode($subject).'?=', $message, $headers);
}
