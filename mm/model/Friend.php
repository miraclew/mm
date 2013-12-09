<?php
define('CK_FRIENDS', 'friends:%s');

class Friend {
	
	private $uid;
	
	public function __construct($uid) {
		$this->uid = $uid;
	}	
	
	public function friends() {
		$redis = new Redis();
		$redis->connect('localhost');
		
		$members = $redis->sMembers(sprintf(CK_FRIENDS, $this->uid));
		
		$friends = array();
		foreach ($members as $value) {
			$user = User::find($value);
			$friends[] = array('uid'=>$user->id, 'name'=>$user->name, 'avatar'=>$user->avatar);
		}
		
		return $friends;
	}
	
}