<?php

/**
 * Created by PhpStorm.
 * User: jakemarvin05
 * Date: 3/11/2015
 * Time: 5:12 AM
 */
namespace Plugin;

class Plugin {
	private $_Name;
	
	public function __construct($pluginName) {
		$className = explode("\\", $pluginName);
		$this->_Name = array_pop($className);
	}
	
	public function introduce() {
		$array = [];
		$array ['name'] = $this->_Name;
		
		var_dump($array);
		return $array;
	}
}
