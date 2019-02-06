<?php
require 'trackdrive.php';

function trackdrive_ping($ids, $checksums, $initial)
{	
	global $trackdrive_api_url;
	$url = $trackdrive_api_url . '/ping';

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
		'ids' => $ids,
		'checksums' => $checksums,
		'initial' => $initial
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

	session_write_close();
	$response = curl_exec($ch);
	curl_close($ch);
	session_start();

	if (!empty($response)){
		list($header, $body) = explode("\r\n\r\n", $response, 2);
		preg_match_all('/^(Set-Cookie:\s*[^\n]*)$/mi', $header, $cookies);
		foreach($cookies[0] AS $cookie)
		{
		     header($cookie, false);
		}
	}
}

trackdrive_ping($_POST['ids'], $_POST['checksums'], $_POST['initial']);