<?php

$key = "HpVhem6yzETzdJHjeYMzAeT913kf9g05";

function salt() {
	
	$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
	$salt = base64_encode($salt);
	$salt = str_replace('+', '.', $salt);

    return $salt;
}

function password_hash($password) {
	
	$salt = salt();
	$hash = crypt($password, '$2y$10$'.$salt.'$');

    return $hash;
}

function password_verify($password,$hash) {
	
	$newhash = crypt($password,$hash);
	
	if ($hash===$newhash) {
	return true;
	} else {
	return false;
	}
}

function crypt_iv() {
	
	// Create the initialization vector for added security.
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);

    return $iv;
}

function crypt_encrypt($string,$iv) {
	
	// Encrypt $string
	$encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_CBC, $iv);
	// Encode to base64 from binary
	$encrypted_string_base64 = base64_encode($encrypted_string);

    return $encrypted_string_base64;
}

function crypt_decrypt($encrypted_string,$iv) {
	
	// Decode to binary from base64
	$encrypted_string = base64_decode($encrypted_string);
	
	// Decrypt $string	
	$decrypted_string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted_string, MCRYPT_MODE_CBC, $iv), "\0");

    return $decrypted_string;
}

