<?php

class dir {

	static $base = '';

	public function __construct($dir = '') {
		
		self::$base = realpath($dir);
		
	}

	public static function base($file = '') {

		return self::$base.DIRECTORY_SEPARATOR.$file;

	}

	public static function layout($file = '', $template = '') {
		
		if($template == '') {
			
			return self::base('layout'.DIRECTORY_SEPARATOR);
			
		}

		return self::base('layout'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function media($file = '') {

		return self::base('media'.DIRECTORY_SEPARATOR.$file);

	}

	public static function classes($file = '') {

		return self::base('lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function vendor($file = '') {

		return self::base('lib'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.$file);

	}

	public static function functions($file = '') {

		return self::base('lib'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function cache($file = '') {

		return self::base('lib'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function backup($file = '') {

		return self::base('lib'.DIRECTORY_SEPARATOR.'backup'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function games($file = '', $cur = false) {
		
		if($cur)
			return self::base('games'.DIRECTORY_SEPARATOR.$cur.DIRECTORY_SEPARATOR.$file);
		
		return self::base('games'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function lang($lang, $file = '') {
	
		return self::base('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.$file);
	
	}
	
	public static function addon($addon, $file = '') {

		return self::base('addons'.DIRECTORY_SEPARATOR.$addon.DIRECTORY_SEPARATOR.$file);

	}

}

?>