<?php

class userPerm {
	
	public static $perms = array();

	public static function add($name, $value) {
		
		self::$perms[$name] = $value;
		
	}
	
	public static function delete($name) {
	
		unset(self::$perms[$name]);
		
	}
	
	public static function is($name) {
		
		return isset(self::$perms[$name]);
		
	}
	
	public static function getAll() {
	
		return self::$perms;
		
	}
	
}


?>