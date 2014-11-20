<?php

class autoload {
	
	
	static $classes = [];
	static $dirs = [];
	static $registered = false;
	static $isNewCache = false;
	
	protected static $composer;
	
	
	/**
	 * Autoload registrieren
	 */
	static public function register() {
		
		if(self::$registered) {
			return;	
		}
		
		self::$composer = include dir::vendor('autoload.php');
		// kein Autoload da wir den PSR-0 Standard von loadClass() benutzen
		self::$composer->unregister();
		
		if(spl_autoload_register([__CLASS__, 'autoloader']) === false) {
			//throw new Exception();
		}
		
		self::loadCache();		
		
        register_shutdown_function([__CLASS__, 'saveCache']);
		
		self::$registered = true;
		
	}
	
	/**
	 * Autoload de-registrieren
	 */
	static public function unregister() {
	
		spl_autoload_unregister([__CLASS__, 'autoloader']);
		self::$registered = false;
		
	}
	
	/**
	 * Die eigentliche Funktion des Autoloader
	 *
	 * @param	string	$class			Der Klassennamen
	 * @return	bool
	 */	
	static public function autoloader($class) {
		
		
		if(self::classExists($class)) {
			return true;
		}
		
		preg_match_all("/(?:^|[A-Z])[a-z]+/", $class, $treffer);
		
		$classPath = implode(DIRECTORY_SEPARATOR, array_map('strtolower', $treffer[0]));
		
		if(isset(self::$classes[$class])) {
			if(is_readable(self::$classes[$class])) {
				self::addClass($class, self::$classes[$class]);
				
				if(self::classExists($class)) {
					return true;
				}				
			}
			
			// Datei im Cache drin, jedoch exsistiert sie nichtmehr
			unset(self::$classes[$class]);
			self::$isNewCache = true;
		}
		
		if(is_readable(dir::classes($classPath.'.php'))) {
			self::addClass($class, dir::classes($classPath.'.php'));
		}
		
		if(self::classExists($class)) {
			return true;	
		}
		
		$classPath = self::$composer->findFile($class);
		if(!is_null($classPath)) {
			self::addClass($class, $classPath);	
		}
		
		return self::classExists($class);
		
	}
	
	/**
	 * Überprüfen ob die Klasse|Trait|Interface exsistiert
	 */
	public static function classExists($class) {
	
		return class_exists($class, false) || trait_exists($class, false) || interface_exists($class, false);
		
	}
	
	/**
	 * Die ganzen Klassen in einer Cache Datei speichern
	 */
	static public function saveCache() {
		
		if(self::$isNewCache) {
			
			$cacheFile = cache::getFileName(0, 'autoloader');
			
			cache::write(json_encode([self::$classes, self::$dirs]), $cacheFile);
			self::$isNewCache = false;
			
		}
		
	}
	
	/**
	 * Den Cache laden
	 */
	static protected function loadCache() {
		
		$cacheFile = cache::getFileName(0, 'autoloadcache');
		
		if(!cache::exist($cacheFile, 3600))
			return;
				
		list(self::$classes, self::$dirs) = json_decode(cache::read($cacheFile), true);
		
	}
	
	/**
	 * Hinzufügen einer Klasse
	 *
	 * @param	string	$path			Der Pfad der Datei
	 */
	static public function addClass($class, $path) {
		
		self::$classes[$class] = $path;
		
		include($path);
		
	}
	
	/**
	 * Einen ganzen Ordner durchscannen und alle Klassen includen
	 *
	 * @param	string	$dir			Der Ordner
	 */
	static public function addDir($dir) {
		
		if(!is_dir($dir)) {
			//throw new Exception;	
		}
		
		// Schon eingescannt
		if(in_array($dir, self::$dirs)) {
			return;	
		}
		
		self::$dirs[] = $dir;
		
		$files = scandir($dir);
		
		foreach($files as $file) {
				
			if(strrchr($file, '.') != '.php')
				continue;
			
			// Ausgedachter Klassennamen
			self::addClass($dir.'_'.$file, $dir.DIRECTORY_SEPARATOR.$file);
			self::$isNewCache = true;
			
		}
		
	}
	
}

?>