<?php

define('CK_FRIENDS', 'friends:%s');
require_once 'api.inc';

function a_list() {
	$current_uid = auth('uid');
	
	$model = new Friend($current_uid);
	
	rs(array('items'=> $model->friends()));
}


function a_create() {
	$current_uid = auth('uid');
	
	$uid = post_argument('uid');
	if ($uid == $current_uid) {
		throw new HttpError("can not add youself as friend", HttpError::BadRequest);
	}
	
	$redis = new Redis();
	$redis->connect('localhost');
	
	$redis->sAdd(sprintf(CK_FRIENDS, $current_uid), $uid);
	
	rs();
}

function a_delete() {
	$current_uid = auth('uid');
	
	$uid = post_argument('uid');
	$redis = new Redis();
	$redis->connect('localhost');
	
	$redis->sRem(sprintf(CK_FRIENDS, $current_uid), $uid);
	
	rs();
}

function a_search() {
	$current_uid = auth('uid');
	
	$keywords = get_argument('keywords');
	$users = User::find('all', array('conditions'=>array("id=? or name like '%$keywords%'", $keywords)));
	
	$friends = array();
	foreach ($users as $user) {
		$friends[] = array('uid'=>$user->id, 'name'=>$user->name, 'avatar'=>$user->avatar);
	}
	
	rs(array('items'=>$friends));
}