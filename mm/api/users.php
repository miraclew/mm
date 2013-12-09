<?php
require_once __DIR__.'/../include/util.inc';
require_once 'api.inc';

function a_index() {
	echo "index";
}

function a_vcode() {
	$cellphone = $_POST['cellphone'];
	$vcode = make_random_vcode();
	$_SESSION['vcode'] = $vcode;
	
	rs(array('vcode' => $vcode));
}

function a_register() {	
// 	$vcode = $_POST['vcode'];
	$password = post_argument('rpassword');
	$name = post_argument('name',null,true);
	
	if (check_username($name)) {
		rf(HttpError::BadRequest, "昵称不合法");
		return;
	}
	
	if (User::exists(array('conditions'=>array("name=?", $name)))) {
		rf(HttpError::BadRequest, "昵称已存在");
		return;
	}
	
	$user = new User(array('password' => $password, 'created_at'=>date('Y-m-d H:i:s'), 'avatar'=> AVATAR_DEFAULT));
	$user->save();
	$user->name = $name;
	$user->save();
	
	$_SESSION['user'] = $user->profile();
	
	rs($user->profile());
}

function a_login() {
	$uid = $_POST['uid'];
	$password = $_POST['password'];
	rdebug($uid);
	rdebug($password);

	$user = User::find('first', array('conditions' => array('uid=? and password=?',$uid, $password)));
	if ($user) {
		$_SESSION['user'] = $user->profile();
	}
	else {
		throw new HttpError("login failed, uid or password incorrect", HttpError::Forbidden);
	}
	
	rs(array('profile' => $user->profile(), 'ws' => WS_CONNECT_URL.'/'.$user->id));
}

function a_logout() {
	if (!empty($_SESSION['user'])) {
		unset($_SESSION['user']);
	}
	
	rs();
}

function a_profile() {
 	$current_uid = auth('uid');

 	$uid = get_argument('uid', $current_uid);
 	if ($uid != $current_uid) {
		$row = User::find($uid);
		$user = $row->profile();
 	}
 	else {
 		rs(auth());
 	}
}

function a_update() {
 	$current_uid = auth('uid');
		
	$name = post_argument('name', '', true);
	$gender = post_argument('gender', 0, true);
	$avatar = file_argument('image/*', 'avatar', '');
	
	$attributes = array();
	if ($name) {
		$attributes['name'] = $name;
	}
	
	if ($avatar) {
		$attributes['avatar'] = $avatar;
	}
	
	if ($gender > 0) {
		$attributes['gender'] = $gender;
	}
	
	if ($attributes) {
		$u = User::find($current_uid);
		$u->update_attributes($attributes);
	}
	
	rs($u->profile());
}

function a_preference() {
 	$current_uid = auth('uid');
	
 	$redis = new Redis();
	$redis->connect('localhost');
	
	$key = sprintf('user.preference:%d', $current_uid);
	$data = $redis->hGetAll($key);
	
	rs(array('preferences' => $data));
}

function a_update_perference() {
	$current_uid = auth('uid');
	
	$redis = new Redis();
	$redis->connect('localhost');
	$key = sprintf('user.preference:%d', $current_uid);
	
	$lbs_enable = post_argument('lbs_enable', '', true);
	if ($lbs_enable) {
		$redis->hSet($key, 'lbs_enable', 1);
	}
	
	rs();
}

