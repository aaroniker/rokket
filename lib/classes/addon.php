<?php

class addon {
	
	public $config = [];
	public $name;
	public $sql;
	public $isChange = false;
	protected $newEntrys = [];
	
	const INSTALL_FILE = 'install.php';
	const UNINSTALL_FILE = 'uninstall.php';
	
	public function __construct($addon, $config = true) {
		
		$this->name = $addon;
		
		if($config) {
						
			$configfile = dir::addon($addon, 'config.json');
			$this->config = json_decode(file_get_contents($configfile), true);
		}
		
		addonConfig::isSaved($addon);
		
		$this->sql = new sql();
		$this->sql->query('SELECT * FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"')->result();
		
	}
	
	public function getSql($name, $default = null) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function get($name, $default = null) {
		
		if(isset($this->config[$name])) {
			return $this->config[$name];
		}
		
		return $default;
		
	}
	
	public function add($name, $value, $toSave = false) {
		
		$this->config[$name] = $value;
		
		if($toSave) {
			$this->isChange = true;
			$this->newEntrys[$name] = $value;
		}
		
	}
	
	public function saveConfig() {
		
		if(!$this->isChange)
			return true;
			
		$newEntrys = array_merge($this->config, $this->newEntrys);
			
		return file_put_contents(dir::addon($this->name, 'config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));
		
	}
	
	public function isInstall() {
	
		return $this->getSql('install', 0) == 1;
		
	}
	
	public function isActive() {
	
		return $this->getSql('active', 0) == 1;
		
	}
	
	public function checkNeed() {
		
		$errors = [];
		foreach($this->get('need', []) as $key=>$value) {
			
			$check = addonNeed::check($key, $value);
			
			if($check !== true) {
				$errors[] = $check;
			}
				
		}
		
		if(!empty($errors)) {
			echo message::danger(implode('<br />', $errors));
			return false;	
		}
		
		return true;
			
	}
	
	public function install() {
		
		if(!$this->checkNeed()) {
			return false;	
		}
		
		$file = dir::addon($this->name, self::INSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
		
		return true;
				
	}
	
	public function uninstall() {
		
		$file = dir::addon($this->name, self::UNINSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
		
		return $this;
				
	}
	
	public function delete() {
	
		$this->uninstall();
		
		$sql = new sql();
		$sql->setTable('addons')
		    ->setWhere('`name` = "'.$this->name.'"')
			->delete();
			
		$dir = dir::addon($this->name);
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		
		foreach($files as $file) {
			
			if ($file->getFilename() === '.' || $file->getFilename() === '..') {
				continue;
			}
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
			
		}
		
		rmdir($dir);
			
		return message::success(sprintf(lang::get('addon_deleted'), $this->name));
		
	}
	
	public function getConfig() {
		
		return $this->config;
		
	}
	
}

?>