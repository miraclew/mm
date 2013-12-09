<?php
function get_argument($name, $default=null, $strip=false) {
	if (empty($_GET[$name])) {
		if ($default !== null) return $default;
		throw new HttpError("argument $name missing", HttpError::BadRequest);
	}

	return $strip ? trim($_GET[$name]) : $_GET[$name];
}

function post_argument($name, $default=null, $strip=false) {
	if (empty($_POST[$name])) {

		if ($default !== null) return $default;
		throw new HttpError("argument $name missing", HttpError::BadRequest);
	}

	return $strip ? trim($_POST[$name]) : $_POST[$name];
}

function file_argument($media_type, $name, $default=null) {
	$mime = check_media_type($media_type);

	// upload file
	if (isset($_FILES[$name])) {
		if ($_FILES[$name]['error'] == UPLOAD_ERR_OK) {
			$destination = get_upload_path($mime, $_FILES[$name]['name']);
			move_uploaded_file($_FILES[$name]['tmp_name'], $destination['path']);
			$media_url = $destination['url'];
		}
		else {
			throw new HttpError('file upload error: '.$_FILES[$name]['error'], HttpError::BadRequest);
		}
	}
	else {
		if ($default !== null) return $default;
		throw new HttpError("argument $name missing", HttpError::BadRequest);
	}

	return $media_url;
}

function get_upload_path($mime, $orgin_filename) {
	$dir =  $mime[0] . '/' . date('Y') . '/' . date('m') . '/' . date('d');
	$save_dir = realpath(UPLOAD_DIR) . '/' . $dir;
	if (!file_exists($save_dir)) {
		mkdir($save_dir, 0777 ,true);
	}

	$filename = md5(uniqid(rand(), true)) . strrchr($orgin_filename, '.');
	return array('path' => $save_dir.'/'.$filename, 'url' => UPLOAD_URL.'/'.$dir.'/'.$filename);
}

function check_media_type($media_type) {
	$mime = explode('/', $media_type);
	$type = $mime[0];
	$subtype = $mime[1];

	if (!in_array($type, array('text','image','audio','video','file'))) {
		throw new HttpError('media type not support', HttpError::BadRequest);
	}

	return $mime;
}

function check_username($username) {
	$RegExp='/^[a-zA-Z0-9_]{3,16}$/'; 
	$len = strlen($username);
	if($len > 15 || $len < 3 || preg_match($RegExp,$username)) {
		return false;
	} else {
		return true;
	}
}

function debug($var) {
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function udebug($uid) {
}

/**
 * write debug info to result
 * @param object $var
 */
$rdebug_msg = "";
function rdebug($var) {
	global $rdebug_msg;
	$rdebug_msg .= print_r($var, true)."\n";
}

