<?php 

$trackdrive_number_data = array();
$trackdrive_number_pings = array('ids' => array(), 'checksums' => array());
$trackdrive_api_url = 'http://trackdrive.net/api/v1/numbers/';
$trackdrive_tokens = trackdrive_get_default_tokens();
$trackdrive_debug = array();
$trackdrive_error;

function show_trackdrive_number($offer_key, $options=array(), $optional_tokens=array())	
{
	echo get_trackdrive_number($offer_key, $options, $optional_tokens);
}

function show_trackdrive_extension($offer_key, $options=array(), $optional_tokens=array())	
{
	echo get_trackdrive_extension($offer_key, $options, $optional_tokens);
}

function get_trackdrive_extension($offer_key, $options=array(), $optional_tokens=array())
{
	global $trackdrive_error;
	// attempt to use a cached extension from the url query params
	if (array_key_exists('tfne', $_GET)){
		return $_GET['tfne'];
	} else {
		try {
			$data = request_trackdrive_number($offer_key, $options, $optional_tokens);
			if ($data && is_array($data) && array_key_exists('number', $data) && array_key_exists('extension', $data['number'])){
				return $data['number']['extension'];
			} else {
				return get_default_extension($options);
			}
		} catch(Exception $e) {
			$trackdrive_error = $e;
			return get_default_extension($options);
		}
	}
}

function get_trackdrive_number($offer_key, $options=array(), $optional_tokens=array())
{
	global $trackdrive_error;
	// process format
	if (array_key_exists('format', $options)){
		$format = $options['format'];
	} else {
		$format = 'human';
	}
	// attempt to use a number from the url
	$cached_number = get_cached_trackdrive_number($format);
	// attempt to use a cached number from the url query params
	if (!empty($cached_number)){
		return $cached_number;
	} else {
		// request a new number from the api
		$method_name = $format . "_number";
		try {
			$data = request_trackdrive_number($offer_key, $options, $optional_tokens);
			if ($data && is_array($data) && array_key_exists('number', $data) && array_key_exists($method_name, $data['number'])){
				return $data['number'][$method_name];
			} else {
				return get_default_number($options);
			}
		} catch(Exception $e) {
			$trackdrive_error = $e;
			return get_default_number($options);
		}
	}
}

function get_cached_trackdrive_number($format)
{
	foreach($_GET as $key => $value)
	{
	   	if ($format == 'human'){
	   		if ($key == 'ftfn' || preg_match('/0ftfn1/', $key)){
	   			return $value;
	   		}
	   	} else {
	   		if ($key == 'tfn' || preg_match('/0tfn1/', $key)){
	   			return $value;
	   		}
	   	}
	}
	return '';
}

function get_default_extension($options)
{
	if (array_key_exists('dne', $_GET)){
		return $_GET['dne'];
	} else if (array_key_exists('default_extension', $options)){
		return $options['default_extension'];
	}
}

function get_default_number($options)
{
	if (array_key_exists('format', $options)){
		$format = $options['format'];
	} else {
		$format = 'human';
	}

	$query_value = get_cached_default_number($format);

	if (!empty($query_value)){
		return $query_value;
	} else 	if (array_key_exists('default_number', $options)){
		return $options['default_number'];
	}
}

function get_cached_default_number($format)
{
	foreach($_GET as $key => $value)
	{
	   	if ($format == 'human'){
	   		if ($key == 'dfn' || preg_match('/0dfn1/', $key)){
	   			return $value;
	   		}
	   	} else {
	   		if ($key == 'dn' || preg_match('/0dn1/', $key)){
	   			return $value;
	   		}
	   	}
	}
	return '';
}

function request_trackdrive_number($offer_key, $options=array(), $optional_tokens=array())
{
	global $trackdrive_number_data;
	global $trackdrive_api_url;
	global $trackdrive_number_pings;
	$cache_key = base64_encode(http_build_query($optional_tokens));
	if (empty($trackdrive_number_data[$cache_key])){
		$data = curl_request_trackdrive_number("$trackdrive_api_url?offer_key=$offer_key", $options, $optional_tokens);
		if ($data){
			$number_data = $trackdrive_number_data[$cache_key] = json_decode($data, true);
			// update ping data
			if (array_key_exists('number', $number_data) && array_key_exists('id', $number_data['number'])) {
				array_push($trackdrive_number_pings['ids'], $number_data['number']['id']);
				array_push($trackdrive_number_pings['checksums'], $number_data['number']['checksum']);
			}
		}
	}
	if (array_key_exists($cache_key, $trackdrive_number_data)){
		return $trackdrive_number_data[$cache_key];	
	} else {
		return false;
	}
}

function enable_pinging_trackdrive_numbers()
{
	global $trackdrive_number_pings;
	if (!empty($trackdrive_number_pings['ids']) && !empty($trackdrive_number_pings['checksums'])){
		$ids = join(',', $trackdrive_number_pings['ids']);
		$checksums = join(',', $trackdrive_number_pings['checksums'])
		?>
		<script>
			(function(){
				function ping(initial) {
					var xhr = new XMLHttpRequest();
					xhr.open('POST', encodeURI('ping.php'));
					xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
					xhr.send(encodeURI('ids=<?php echo $ids ?>&checksums=<?php echo $checksums ?>&initial=' + initial));
				};
				setTimeout(function(){
					ping('true');
				}, 100);
			})();
		</script>
		<?php
	}
}

function curl_request_trackdrive_number($url, $options=array(), $optional_tokens=array())
{
	global $trackdrive_debug;
	$ch = curl_init();
	$ip = $_SERVER['REMOTE_ADDR'];
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// get http header for cookies
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("X_FORWARDED_FOR: $ip"));

	// add POST data
	$fields = array(
		'referrer_url' => base64_encode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"),
		'referrer_tokens' => base64_encode(http_build_query($optional_tokens))
		);

	$fields_string = http_build_query($fields);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

	// forward current cookies to curl
	$cookies = array();
	foreach ($_COOKIE as $key => $value)
	{
		if ($key != 'Array')
		{
			$cookies[] = $key . '=' . $value;
		}
	}
	curl_setopt( $ch, CURLOPT_COOKIE, implode(';', $cookies) );
	// Stop session so curl can use the same session without conflicts
	session_write_close();

	$response = curl_exec($ch);
	curl_close($ch);

	// Session restart
	session_start();

	$trackdrive_debug = $response;

	if (empty($response)){
		$body = false;
	} else {
		// Seperate header and body
		list($header, $body) = explode("\r\n\r\n", $response, 2);

		// extract cookies form curl and forward them to browser
		preg_match_all('/^(Set-Cookie:\s*[^\n]*)$/mi', $header, $cookies);
		foreach($cookies[0] AS $cookie)
		{
			header($cookie, false);
		}
	}
	return $body;
}

function trackdrive_get_default_tokens()
{
	$full_url = $_SERVER['REQUEST_URI'];
	$osversion = trackdrive_get_os();
	return array(
		'full_url' => $full_url,
		'source_host' => $_SERVER['HTTP_HOST'],
		'source_pathname' => parse_url( $full_url, PHP_URL_PATH ),
		'source_query' => parse_url( $full_url, PHP_URL_QUERY ),
		'ip' => $_SERVER['REMOTE_ADDR'],
		'useragent' => $_SERVER['HTTP_USER_AGENT'],
		'osversion' => $osversion,
		'os' => strtok($osversion, " "),
		'browser' => trackdrive_get_browser(),
		'browserversion' => trackdrive_get_browser_version()
		);
}

// Get the browser, browserversion, os, and osversion from the user agent
function trackdrive_get_os() {
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	$os_platform    =   "Unknown OS Platform";
	$os_array       =   array(
		'/windows nt 10.0/i'    =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/win98/i'              =>  'Windows 98',
		'/win95/i'              =>  'Windows 95',
		'/win16/i'              =>  'Windows 3.11',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
		);
	foreach ($os_array as $regex => $value) {
		if (preg_match($regex, $useragent)) {
			$os_platform    =   $value;
		}
	}
	return $os_platform;
}

function trackdrive_get_browser() {
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	$browser_name        =   "Unknown Browser";
	$browser_array  =   array(
		'/edge/i'       =>  'Microsoft Edge',
		'/msie/i'       =>  'Internet Explorer',
		'/firefox/i'    =>  'Firefox',
		'/safari/i'     =>  'Safari',
		'/chrome/i'     =>  'Chrome',
		'/opera/i'      =>  'Opera',
		'/netscape/i'   =>  'Netscape',
		'/maxthon/i'    =>  'Maxthon',
		'/konqueror/i'  =>  'Konqueror',
		'/mobile/i'     =>  'Handheld Browser'
		);

	foreach ($browser_array as $regex => $value) {
		if (preg_match($regex, $useragent)) {
			$browser_name    =   $value;
		}
	}
	return $browser_name;
}

function trackdrive_get_browser_version() {
	$version= "";
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	$browser = trackdrive_get_browser();
	$known = array('Version', $browser, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $useragent, $matches)) {
        // we have no matching number just continue
	}

    // see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
		if (strripos($useragent,"Version") < strripos($useragent,$browser)){
			$version= $matches['version'][0];
		} else {
			$version= $matches['version'][1];
		}
	} else {
		$version= $matches['version'][0];
	}

    // check if we have a number
	if ($version==null || $version=="") {$version="?";}
	return $version;
}

