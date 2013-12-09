<?php
class User extends Model {
	
	public function profile() {
		return array('uid'=>$this->id, 'name'=>$this->name, 'avatar'=>$this->avatar);
	}
}