<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 06.05.15
 * Time: 13:02
 */
class Page_meta extends Model {
}

class Page_has_template extends Model {
	public function template() {
		return $this->has_one('Template');
	}
}

class Hook_value extends Model {
}

class Page extends Model {
	public function meta() {
		return $this->has_one('Page_meta');
	}

	public function templates() {
		return $this->has_many('Page_has_template');
	}

	public function hook_values() {
		return $this->has_many('Hook_value');
	}
}