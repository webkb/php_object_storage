<?php
define('UCLOUD_ACCESSKEYID', '');
define('UCLOUD_ACCESSKEYSECRET', '');
define('UCLOUD_BUCKET', '');
define('UCLOUD_HOST', '');



function UCloud对象存储 ($method, $filename, $file = '') {
	$accessKeyId		= UCLOUD_ACCESSKEYID;
	$accessKeySecret	= UCLOUD_ACCESSKEYSECRET;
	$bucket				= UCLOUD_BUCKET;
	$domain				= UCLOUD_HOST;

	$url				= "http://$bucket.$domain/$filename";
	if (strtoupper($method) == 'GET') {
		$filename = '';
	}
	if ($file) {
		$mime = mime_content_type($file);
	} else {
		$mime = '';
	}
	$options			= join("\n", array(
		strtoupper($method),
		'',
		$mime,
		'',
		"/$bucket/$filename"
	));
	$hash				= hash_hmac(
		'sha1',
		$options,
		$accessKeySecret,
		true
	);
	$auth				= $accessKeyId . ':' . base64_encode($hash);
	$headers			= array(
		"Expect: ",
		"Content-Type: $mime",
		"Authorization: UCloud $auth"
	);

	if ($file) {
		$postfileds = file_get_contents($file);
		return curl($url, $headers, strtoupper($method), '', $postfileds);
	} else {
		return curl($url, $headers, strtoupper($method));
	}
}
function UCloud对象存储上传 ($file, $filename) {
	return UCloud对象存储('PUT', $filename, $file);
}
function UCloud对象存储删除 ($filename) {
	return UCloud对象存储('DELETE', $filename);
}
function UCloud对象存储列表 ($query = '') {
	$jsonlist = UCloud对象存储('GET', '?list' . $query);
	$objectlist			= json_decode($jsonlist);
	$objectlist->Contents = $objectlist->DataSet; unset($objectlist->DataSet);
	return $objectlist;
}