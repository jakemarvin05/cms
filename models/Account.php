<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 30.04.15
 * Time: 16:04
 */
class Role extends Model {
}

class Account_has_role extends Model {
	public function role() {
		return $this->has_one('Role','id','role_id');
	}
}

class Account extends Model {
	public function roles() {
		return $this->has_many('Account_has_role');
	}
}