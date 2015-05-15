<?php

class lang {
	
	static $lang;
	static $langs = [];
	static $default = [];
	static $defaultLang = 'en_gb';
	
	static public function setLang($lang = 'en_gb') {
		
		if(is_dir(dir::lang($lang))) {
			
			self::$lang = $lang;	
			self::loadLang(dir::lang(self::getLang(), 'main.json'));
			
		}
		
	}
	
	static public function get($name, $lang = false) {
		
		if($lang)
			return $lang[$name];
			
		if(isset(self::$langs[$name])) {
			return self::$langs[$name];	
		}
		
		if(isset(self::$default[$name])) {
			return self::$default[$name];
		}
		
		return $name;
		
	}
	
	static public function getLang() {
		
		return self::$lang;
			
	}
	
	static public function getLangs() {
		
		$dirs = [];
		
		$dir = new DirectoryIterator(dir::lang());
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				
				$dirs[$fileinfo->getFilename()] = self::get('lang', self::loadLang(dir::lang($fileinfo->getFilename(), 'main.json')));
			}
		}
		
		return $dirs;
			
	}
	
	static public function getDefaultLang() {
		
		return self::$defaultLang;
		
	}
	
	static public function loadLang($file, $defaultLang = false) {
		
		$file = file_get_contents($file);
		
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
		$array = json_decode($file, true);
		
		if(!$defaultLang) {
			self::$langs = array_merge((array)$array, self::$langs);
		} else {
			self::$default = array_merge((array)$array,self:: $default);
		}
		
		return self::$langs = $array;
		
	}
	
	static public function setDefault() {
			
		$file = dir::lang(self::getDefaultLang(), 'main.json');
					
		self::loadLang($file, true);
		
	}
	
}

?>