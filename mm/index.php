<?php

$pathinfo = explode('/', $_SERVER['PATH_INFO']);

if (count($pathinfo) < 2) {
	echo "index";
}

$resource = $pathinfo[1];
$action = isset($pathinfo[2]) ? $pathinfo[2] : 'index';

require_once "api/$resource.php";

$func = 'a_'.$action;

echo $func;

if (function_exists($func)) {
	$func();
}
else {
	throw new HttpError('action not exist', HttpError::MethodNotAllowed);
}



