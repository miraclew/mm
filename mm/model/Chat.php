<?php
/**
 * 对话
 *
 * @property int $id
 * @property string $hash
 * @property array $members
 * @property datetime $created
 * @property Redis $redis
 */
class Chat extends Model {
	
	public static function create($creatorid, $members) {
		$uids = explode(',', $members);
		$uids[] = $creatorid; // add creatorid
		$uids = array_unique($uids);
		
		if (count($uids) < 2) {
			throw new HttpError('members should more than 2', HttpError::BadRequest);
		}
		
		sort($uids);
		$hash = md5(implode(',', $uids));
		
		$chat = self::find_by_hash($hash);
		if (!$chat) {
			$chat = new self(array('hash'=>$hash, 'creatorid'=>$creatorid));
			$chat->save();
		}
		
		$chat_redis = new ChatRedis($chat->id);
		foreach ($uids as $value) {
			if(!$value) continue;
			$chat_redis->members_add($value);
		}
		
		return $chat;
	}
	
	public function get_title($uid=0) {
		$chat_redis = new ChatRedis($this->id);
		$uids = $chat_redis->members();
		if (count($uids) > 2 || $uid == 0) {
			$names = array();
			foreach ($uids as $value) {
				$profile = User::find($value);
				$names[] = $profile->name;
			}
			$title = implode(',', $names) . ' ('.count($uids).')';
			
		}
		else {
			$other = $uids[0] == $uid ? $uids[1] : $uids[0];
			$profile = User::find($other);
			$title = $profile->name;
		}
		return $title;
	}
	
	public function get_members() {
		$chat_redis = new ChatRedis($this->id);
		
		$users = array();
		foreach ($chat_redis->members() as $value) {
			$users[] = User::find($value);
		}
		
		return $users;
	}	
	
	public function get_avatar($uid) {
		$users = $this->members;
		$avatar = '';
		if (count($users) == 2) {
			foreach ($users as $value) {
				if ($value->uid != $uid) {
					$avatar = $value->avatar;
					break;
				}
			}
		}
		else { // group talk, use default group avatar
			$avatar = GROUP_AVATAR_DEFAULT;
		}
		
		return $avatar;
	}
	
	public function info($uid) {
		$data = $this->attributes();
		$data['title'] = $this->get_title($uid);
		$data['avatar'] = $this->get_avatar($uid);
		return $data;	
	}
}

class ChatRedis {
	public function __construct($id) {
		$this->id = $id;
		$this->key = "chat:$id";
		$redis = new Redis();
		$redis->connect('localhost');
		$this->redis = $redis;
	}
	
	public function members() {		
		$members = $this->redis->sMembers($this->key);
		return $members;
	}
	
	public function members_add($uid) {
		$this->redis->sAdd($this->key, $uid);
	}
	
	public function members_delete($uid) {
		$this->redis->sRem($this->key, $uid);
	}
}