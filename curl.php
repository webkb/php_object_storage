<?php
function curl ($url, $headers, $method = '', $filepath = '', $postfileds = '') {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	if ($method) {
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
	}

	if ($filepath) {
		curl_setopt($curl_handle, CURLOPT_UPLOAD, 1);
		$file_handle = fopen($filepath, 'rb');
		curl_setopt($curl_handle, CURLOPT_READDATA, $file_handle);
	} elseif ($postfileds) {
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfileds);
	}

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);//var_dump($info);
	curl_close($curl_handle);

	if ($filepath) {
		fclose($file_handle);
	}

	return $response;
}