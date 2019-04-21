<?php
define('WANGYI_ACCESSKEYID', '');
define('WANGYI_ACCESSKEYSECRET', '');
define('WANGYI_BUCKET', '');
define('WANGYI_HOST', '');



function 网易对象存储 ($method, $filename, $file = '') {
	$accessKeyId		= WANGYI_ACCESSKEYID;
	$accessKeySecret	= WANGYI_ACCESSKEYSECRET;
	$bucket				= WANGYI_BUCKET;
	$domain				= WANGYI_HOST;

	$url				= "http://$bucket.$domain/$filename";

	$date				= gmdate('D, d M Y H:i:s \G\M\T');
	$options			= join("\n", array(
		strtoupper($method),
		'',
		'',
		$date,
		"/$bucket/$filename"
	));
	$hash				= hash_hmac(
		'sha256',
		$options,
		$accessKeySecret,
		true
	);
	$auth				= $accessKeyId . ':' . base64_encode($hash);
	$headers			= array(
		"Host: $bucket.$domain",
		"Date: $date",
		"Authorization: NOS $auth"
	);

	if ($file) {
		return curl($url, $headers, strtoupper($method), $file);
	} else {
		return curl($url, $headers, strtoupper($method));
	}
}
function 网易对象存储上传 ($file, $filename) {
	return 网易对象存储('PUT', $filename, $file);
}
function 网易对象存储删除 ($filename) {
	return 网易对象存储('DELETE', $filename);
}
function 网易对象存储列表 ($query = '') {
	$xmllist = 网易对象存储('GET', $query);
	$objectlist = simplexml_load_string($xmllist, 'SimpleXMLElement', LIBXML_NOCDATA);
	return $objectlist;
}