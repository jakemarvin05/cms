<?php
/**
 * Created by PhpStorm.
 * User: dusia
 * Date: 07.05.15
 * Time: 11:16
 */
class Hook extends Model {
}

class Category extends Model {
}

class Tag extends Model {
}

class Template_type extends Model {
}

class Template_has_tag extends Model {
}

class Template_has_category extends Model {
}

class Template extends Model {
	public function type() {
		return $this->has_one('template_type','id','template_type_id');
	}

	public function pages() {
		return $this->has_many('page_has_template');
	}

	public function hooks() {
		return $this->has_many('hook');
	}

	public function tags() {
		return $this->has_many('template_has_tag');
	}

	public function cats() {
		return $this->has_many('template_has_category');
	}

	public static function has_cat($orm, $id) {
		return $orm->where('id', $id);
	}

	public static function has_tag($orm, $id) {
		return $orm->where('id', $id);
	}
}