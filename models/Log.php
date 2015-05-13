<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 05.05.15
 * Time: 10:39
 */
class Log_type extends Model {
}

class Log extends Model {
	public function types() {
		return $this->has_one('log_type');
	}
}