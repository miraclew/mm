<?php

require_once __DIR__.'/../include/util.inc';
require_once 'api.inc';

function a_index() {
	$current_uid = auth('uid');
	
	$redis = new Redis();
	$redis->connect('localhost');
}

function a_show() {
	$current_uid = auth('uid');
	$chatid = get_argument('chatid');
	
	$chat = Chat::find($chatid);
	
	rs(array('chat' => $chat->info($current_uid)));
}

function a_create() {
	$current_uid = auth('uid');
	
	$members = post_argument('members');
	$chat = Chat::create($current_uid, $members);
	
	rs(array('chat' => $chat->info($current_uid)));
}

function a_message_send() {
	$current_uid = auth('uid');
	
// 	print_r($_REQUEST);
	$chatid = post_argument('chatid');
	$text = post_argument('text');
	$media_type = post_argument('media_type', 'text/plain');
	$media_url = file_argument($media_type, 'media', '');
		
	$message = Message::create(array('uid'=>$current_uid, 'chatid'=>$chatid, 'text'=>$text, 
			'media_type'=>$media_type, 'media_url'=>$media_url));
	
	$data = $message->attributes();
	$profile = User::find($current_uid)->profile();
	$data['user'] = $profile;
	
	// send to queue
	$result = http_post(WS_API_URL, array('msg' => json_encode($data)));
	
	rs(array('message'=> $data, 'debug'=>$result));
}

function a_message_list() {
	$current_uid = auth('uid');
	
	$chatid = get_argument('chatid');
	$since = get_argument('since',0);
	
	$messages = Message::find('all', array('conditions' => array('chatid=?',$chatid)));
	
	$data = array();
	foreach ($messages as $value) {
		$item = $value->attributes();
		$profile = User::find($value->uid)->profile();
		$item['user'] = $profile;
		
		$data[] = $item;
	}
	
	rs($data);
}