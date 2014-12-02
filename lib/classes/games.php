<?php

class games {
	
	static $params = [];
	
	static $games = [];
	
	static public function getConfig($game) {
		
		$file = file_get_contents(dir::games('config.json', $game));
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);
		
		self::$params = json_decode($file, true);
		
		return self::$params;
		
	}
	
	static public function getControl($game) {
		
		return file_get_contents(dir::games('control.sh', $game));
		
	}
	
	static public function replaceControl($game, $array) {
		
		$keys = [];
		$vals = [];
		
		$emailNot = (rp::get('emailNot')) ? 'on' : 'off';
		
		$array = array_merge($array, ['ip' => rp::get('ip'), 'email' => rp::get('email'), 'emailNot' => $emailNot]);
		
		foreach($array as $key => $val) {
			$keys[] = '{{'.$key.'}}';
			$vals[] = $val;
		}
		
		return str_replace($keys, $vals, self::getControl($game));
		
	}
	
	static public function getAll() {
		
		$handle = opendir(dir::games(''));
		
		while($file = readdir($handle)) {
		
			if(in_array($file, ['.', '..']))
        		continue;
				
			self::$games[] = self::getConfig($file);
		
		}
		
		return self::$games;
		
	}
	
}

?>