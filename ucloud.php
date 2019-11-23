<?php
define('UCLOUD_ACCESSKEYID', '');
define('UCLOUD_ACCESSKEYSECRET', '');
define('UCLOUD_BUCKET', '');
define('UCLOUD_HOST', '');



function UCloud对象存储($method, $filename = '', $filepath = '', $query = '') {
	$accessKeyId        = UCLOUD_ACCESSKEYID;
	$accessKeySecret    = UCLOUD_ACCESSKEYSECRET;
	$bucket             = UCLOUD_BUCKET;
	$domain             = UCLOUD_HOST;

	if ($filename) {
		$url            = "http://$bucket.$domain/$filename";
	} elseif ($query) {
		$url            = "http://$bucket.$domain/$query";
	}

	if ($filepath) {
		$mime           = mime_content_type($file);
	} else {
		$mime           = '';
	}
	$options            = join("\n", array(
		strtoupper($method),
		'',
		$mime,
		'',
		"/$bucket/$filename"
	));
	$hash               = hash_hmac(
		'sha1',
		$options,
		$accessKeySecret,
		true
	);
	$auth               = $accessKeyId . ':' . base64_encode($hash);
	$headers            = array(
		"Content-Type: $mime",
		"Authorization: UCloud $auth"
	);

	if ($filepath) {
		$postfileds = file_get_contents($filepath);
		return curl($url, $headers, $method, '', $postfileds);
	} else {
		return curl($url, $headers, $method);
	}
}
function UCloud对象存储上传 ($filename, $filepath) {
	return UCloud对象存储('PUT', $filename, $filepath);
}
function UCloud对象存储删除 ($filename) {
	return UCloud对象存储('DELETE', $filename);
}
function UCloud对象存储列表 ($query = '?list') {
	$jsonlist = UCloud对象存储('GET', '', '', $query);
	$objectlist = json_decode($jsonlist);
	$objectlist->Contents = $objectlist->DataSet; unset($objectlist->DataSet);
	return $objectlist;
}