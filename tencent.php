<?php
define('TENCENT_APP_ID', '');
define('TENCENT_ACCESSKEYID', '');
define('TENCENT_ACCESSKEYSECRET', '');
define('TENCENT_BUCKET', '');
define('TENCENT_HOST', '');



function 腾讯对象存储 ($method, $filename = '', $file = '',$query = '') {
	$app_id             = TENCENT_APP_ID;
	$accessKeyId        = TENCENT_ACCESSKEYID;
	$accessKeySecret    = TENCENT_ACCESSKEYSECRET;
	$bucket             = TENCENT_BUCKET;
	$domain             = TENCENT_HOST;

	$url = "http://$bucket-$app_id.$domain/$filename$query";

	$signTime = time() . ';' . (time() + 3600);
	$options            = sha1(join("\n", array(
		strtolower($method),
		"/$filename",
		'',
		"host=$bucket-$app_id.$domain",
		''
	)));
	$signOptions        = join("\n", array(
		'sha1',
		$signTime,
		$options,
		''
	));
	$signSecret         = hash_hmac(
		'sha1',
		$signTime,
		$accessKeySecret
	);
	$hash               = hash_hmac(
		'sha1',
		$signOptions,
		$signSecret
	);
	$auth               = str_replace('%3B', ';', http_build_query(array(
		'q-sign-algorithm' => 'sha1',
		'q-ak' => $accessKeyId,
		'q-sign-time' => $signTime,
		'q-key-time' => $signTime,
		'q-header-list' => 'host',
		'q-url-param-list' => '',
		'q-signature' => $hash
	)));
	$headers            = array(
		"Authorization: $auth"
	);

	if ($file) {
		return curl($url, $headers, $method, $file);
	} else {
		return curl($url, $headers, $method);
	}
}
function 腾讯对象存储上传 ($file, $filename) {
	return 腾讯对象存储('PUT', $filename, $file);
}
function 腾讯对象存储删除 ($filename) {
	return 腾讯对象存储('DELETE', $filename);
}
function 腾讯对象存储列表 ($query = '') {
	$xmllist = 腾讯对象存储('GET', '', '', $query);
	$objectlist = simplexml_load_string($xmllist, 'SimpleXMLElement', LIBXML_NOCDATA);
	return $objectlist;
}