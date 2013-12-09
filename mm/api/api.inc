<?php
require_once '../include/init.inc';

header('Content-Type: application/json; charset=utf-8');

function rs($data=null) {
	global $rdebug_msg;
	$result = array('code'=>0, 'data'=>$data);
	if ($rdebug_msg) {
		$result['debug'] = $rdebug_msg;
	}
	
	echo json_encode($result);
}

function rf($code, $error) {
	global $rdebug_msg;
	$result = compact('code','error');
	if ($rdebug_msg) {
		$result['debug'] = $rdebug_msg;
	}
	
	echo json_encode($result);
}

// action
$a = 'index';
if (!empty($_REQUEST['a'])) {
	$a = $_REQUEST['a'];
}

$func = 'a_'.$a;
if (function_exists($func)) {
	if (DEBUG > 0) {
		$func();
	}
	else {
		try {
			$func();
		} catch (HttpError $e) {
			rf($e->getCode(), $e->getMessage());
		}
	}
}
else {
	rf(HttpError::MethodNotAllowed, 'action not exist');
}
