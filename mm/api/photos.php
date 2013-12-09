<?php
require_once 'api.inc';

function a_list() {
	$current_uid = auth('uid');
	
	$items = array();
	$photos = Photo::find_all_by_uid($current_uid);
	
	foreach ($photos as $value) {
		$items[] = array('id'=> $value->id, 'photo' => $value->photo);
	}
	
	rs(array('items' => $items));
}


function a_create() {
	$current_uid = auth('uid');
	
	$photo_url = file_argument('image/*', 'photo');
	$photo = Photo::create(array('photo'=>$photo_url, 'uid'=>$current_uid));
	
	rs(array('photo'=>$photo->attributes()));
}

function a_delete() {
	$current_uid = auth('uid');
	
	$id = post_argument('id');
	$photo = Photo::find($id);
	if (!$photo) {
		throw new HttpError('photo not exist', HttpError::NotFound);
	}
	if ($photo->uid != $current_uid) {
		throw new HttpError('not allowed', HttpError::Forbidden);
	}
	
	$photo->delete();
	
	rs();
}
