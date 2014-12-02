<?php

class addonConfig {
	
	static $all = [];
	static $allConfig = [];
	
	public static function isSaved($addon, $save = true) {
	
		$sql = new sql();
		$num = $sql->num('SELECT 1 FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"');
		if(!$num && $save) {
			$save = new sql();
			$save->setTable('addons');
			$save->addPost('name', $addon);
			$save->save();
		}
		
		return $num;
		
	}
	
	public static function getAll() {

		if(!count(self::$all)) {

			$sql = new sql();		
			$sql->query('SELECT name FROM '.sql::table('addons').' WHERE `install` = 1  AND `active` = 1')->result();
            while($sql->isNext()) {
				self::$all[] = $sql->get('name');
				$sql->next();		
			}
			
		}

		return self::$all;

	}
	
	public static function includeAllLibs() {
		
		foreach(self::getAll() as $name) {

            $dir = dir::addon($name, 'vendor');
            if(file_exists($dir)) {
                autoload::addDir($dir);
            }
			
			$dir = dir::addon($name, 'lib');
			if(file_exists($dir)) {
				autoload::addDir($dir);
			}

		}
		
	}
	
	public static function includeAllConfig() {
		
		$return = [];
		foreach(self::getAll() as $name) {
			$return[] = dir::addon($name, 'config.php');
		}
		return $return;
		
	}
	
	public static function includeAllLangFiles() {
		
		foreach(self::getAll() as $name) {
			
			$file = dir::addon($name, 'lang/'.lang::getLang().'.json');
			if(file_exists($file)) {				
				lang::loadLang($file);
			}
			
			$defaultFile = dir::addon($name, 'lang/'.lang::getDefaultLang().'.json');
			if(file_exists($defaultFile)) {				
				lang::loadLang($defaultFile, true);
			}
			
		}
		
	}
	
	public static function getConfig($name) {
	
		$configFile = dir::addon($name, 'config.json');
		if(file_exists($configFile)) {
			return json_decode(file_get_contents($configFile), true);
		}
		
		return false;
			
	}
	
	public static function loadAllConfig() {
		
		$addons = [];
		foreach(self::getAll() as $name) {
			$addons[$name] = self::getConfig($name);
		}
			
		rp::add('addons', $addons);
		
		return true;
		
	}
	
	public static function isActive($name) {
		
		return in_array($name, self::$all);
	}
	
}

?>
