<?php
define('AWS_ACCESSKEYID', '');
define('AWS_ACCESSKEYSECRET', '');
define('AWS_BUCKET', '');
define('AWS_SERVICE', 's3');
define('AWS_ENDPOINT', '');
define('AWS_HOST', 'amazonaws.com');
define('AWS_VERSION', 'aws4_request');



function aws_signature ($method, $filename, $query, $bodyHash, $date_l, $date_s, $signatureItem, $signatureHeader) {
	$accessKeySecret    = AWS_ACCESSKEYSECRET;
	$host				= AWS_BUCKET . '.' . AWS_SERVICE . '.' . AWS_ENDPOINT . '.' . AWS_HOST;
	$endPoint			= AWS_ENDPOINT;
	$service			= AWS_SERVICE;
	$version			= AWS_VERSION;

	$query				= str_replace('?', '', $query);
	$option_header		= join("\n", array(
		$method,
		"/$filename",
		$query,
		"host:$host",
		"x-amz-content-sha256:$bodyHash",
		"x-amz-date:$date_l",
		"",
		$signatureHeader,
		$bodyHash
	));
	$optionHash			= hash('sha256', $option_header);
	$optionItem         = join("\n", array(
		"AWS4-HMAC-SHA256",
		$date_l,
		$signatureItem,
		$optionHash
	));

	$itemHash	= hash_hmac('sha256', $date_s,		"AWS4$accessKeySecret",	true);
	$itemHash	= hash_hmac('sha256', $endPoint,	$itemHash,				true);
	$itemHash	= hash_hmac('sha256', $service,		$itemHash,				true);
	$itemHash	= hash_hmac('sha256', $version,		$itemHash,				true);

	$hash               = hash_hmac(
		'sha256',
		$optionItem,
		$itemHash
	);
	return $hash;
}

function aws_auth ($signatureItem, $signatureHeader, $signature) {
	$accessKeyId        = AWS_ACCESSKEYID;
	return "AWS4-HMAC-SHA256 Credential=$accessKeyId/$signatureItem, SignedHeaders=$signatureHeader, Signature=$signature";
}

function aws_header ($host, $bodyHash, $date_l, $auth) {
	return array(
		"Host: $host",
		"X-Amz-Content-Sha256: $bodyHash",
		"x-amz-date: $date_l",
		"Authorization: $auth"
	);
}

function 亚马逊对象存储 ($method, $filename = '', $filepath = '', $query = '') {
	$accessKeyId        = AWS_ACCESSKEYID;
	$accessKeySecret    = AWS_ACCESSKEYSECRET;
	$host				= AWS_BUCKET . '.' . AWS_SERVICE . '.' . AWS_ENDPOINT . '.' . AWS_HOST;
	$endPoint			= AWS_ENDPOINT;
	$service			= AWS_SERVICE;
	$version			= AWS_VERSION;

	if ($filename) {
		$url            =  "http://$host/$filename";
	} else {
		$url            =  "http://$host/$query";
	}

	$date_l				= gmdate('Ymd\THis\Z');
	$date_s				= substr($date_l, 0, 8);

	if ($filepath) {
		$bodyHash		= hash('sha256', file_get_contents($filepath));
	} else {
		$bodyHash		= hash('sha256', '');
	}


	$signatureItem		= "$date_s/$endPoint/$service/$version";

	$signatureHeader	= 'host;x-amz-content-sha256;x-amz-date';

	$signature			= aws_signature($method, $filename, $query, $bodyHash, $date_l, $date_s, $signatureItem, $signatureHeader);
	$auth				= aws_auth($signatureItem, $signatureHeader, $signature);
	$headers            = aws_header ($host, $bodyHash, $date_l, $auth);

	if ($filepath) {
		$postfileds		= file_get_contents($filepath);
		return curl($url, $headers, $method, '', $postfileds);
	} else {
		return curl($url, $headers, $method);
	}
}
function 亚马逊对象存储上传 ($filename, $filepath) {
	return 亚马逊对象存储('PUT', $filename, $filepath);
}
function 亚马逊对象存储删除 ($filename) {
	return 亚马逊对象存储('DELETE', $filename);
}
function 亚马逊对象存储列表 ($query = '') {
	$xmllist = 亚马逊对象存储('GET', '', '', $query);
	$objectlist = simplexml_load_string($xmllist, 'SimpleXMLElement', LIBXML_NOCDATA);
	return $objectlist;
}