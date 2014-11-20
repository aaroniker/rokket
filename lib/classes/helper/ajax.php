<?php

class ajax {
	
	static $return = [];
	
	public static function is() {
		
		return type::server('HTTP_X_REQUESTED_WITH', 'string', '') == 'XMLHttpRequest';
		
	}
	
	public static function addReturn($text) {
		self::$return[] = $text;
		
	}
	
	public static function getReturn() {
	
		return implode('<br />', self::$return);
		
	}
	
}

?>