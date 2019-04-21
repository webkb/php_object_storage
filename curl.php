<?php
function curl ($url, $headers, $method = '', $file = '', $postfileds = '') {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	if ($method) {
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $method);
	}

	if ($file) {
		curl_setopt($curl_handle, CURLOPT_UPLOAD, 1);
		$file_handle = fopen($file, 'rb');
		curl_setopt($curl_handle, CURLOPT_READDATA, $file_handle);
	}
	if ($postfileds) {
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfileds);
	}

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	if ($file) {
		fclose($file_handle);
	}

	return $response;
}
function curl_put ($url, $headers, $file_handle) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_UPLOAD, 1);
	curl_setopt($curl_handle, CURLOPT_READDATA, $file_handle);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}
function curl_put_with ($url, $headers, $postfileds) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfileds);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}
function curl_post ($url, $headers, $postfileds) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postfileds);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}
function curl_post_without ($url, $headers) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}
function curl_get ($url, $headers) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}
function curl_delete ($url, $headers) {

	$curl_handle = curl_init();

	curl_setopt($curl_handle, CURLOPT_URL, $url);
	curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($curl_handle);//var_dump($response);
	$info = curl_getinfo($curl_handle);var_dump($info);
	curl_close($curl_handle);

	return $response;
}