<?php
define('QINIU_ACCESSKEYID', '');
define('QINIU_ACCESSKEYSECRET', '');
define('QINIU_BUCKET', '');
define('QINIU_UP_HOST', 'http://up.qiniup.com');
define('QINIU_RSF_HOST', 'http://rsf.qiniu.com');
define('QINIU_RS_HOST', 'http://rs.qiniu.com');



function qn_base64_encode ($data) {
	return str_replace(array('+', '/'), array('-', '_'), base64_encode($data));
}
function 七牛对象存储上传 ($file, $filename) {
	$accessKeyId        = QINIU_ACCESSKEYID;
	$accessKeySecret    = QINIU_ACCESSKEYSECRET;
	$bucket             = QINIU_BUCKET;

	$url                = QINIU_UP_HOST;

	$options            = json_encode(array(
		'scope' => $bucket,
		'deadline' => time() + 3600
	));

	$hash               = hash_hmac(
		'sha1',
		qn_base64_encode($options),
		$accessKeySecret,
		true
	);
	$auth               = $accessKeyId . ':' . qn_base64_encode($hash) . ':' . qn_base64_encode($options);

	$headers            = array(
		'Content-Type: multipart/form-data'
	);

	$postfileds         = array(
		'token'         => $auth,
		'key'           => $filename,
		'file'          => new CURLFile($file)
	);

	return curl($url, $headers, 'POST', '', $postfileds);
}
function 七牛对象存储列表 ($query = '') {
	$accessKeyId        = QINIU_ACCESSKEYID;
	$accessKeySecret    = QINIU_ACCESSKEYSECRET;
	$bucket             = QINIU_BUCKET;

	$options            = "/list?bucket=$bucket$query";

	$url                = QINIU_RSF_HOST . $options;

	$hash               = hash_hmac(
		'sha1',
		$options . "\n",
		$accessKeySecret,
		true
	);
	$auth               = $accessKeyId . ':' . qn_base64_encode($hash);

	$headers            = array(
		"Authorization: QBox $auth"
	);

	$jsonlist           = curl($url, $headers, 'GET');
	$objectlist         = json_decode($jsonlist);
	if (isset($objectlist->marker)) {
		$objectlist->NextMarker = $objectlist->marker; unset($objectlist->marker);
	} else {
		$objectlist->NextMarker = '';
	}
	$objectlist->Contents = $objectlist->items; unset($objectlist->items);
	return $objectlist;
}
function 七牛对象存储删除 ($filename) {
	$accessKeyId        = QINIU_ACCESSKEYID;
	$accessKeySecret    = QINIU_ACCESSKEYSECRET;
	$bucket             = QINIU_BUCKET;

	$options            = '/delete/' . qn_base64_encode($bucket . ':' . $filename);

	$url                = QINIU_RS_HOST . $options;

	$hash               = hash_hmac(
		'sha1',
		$options . "\n",
		$accessKeySecret,
		true
	);
	$auth               = $accessKeyId . ':' . qn_base64_encode($hash);

	$headers            = array(
		"Authorization: QBox $auth"
	);

	return curl($url, $headers, 'POST');
}