<?php

class server {

	protected $id;
	
	protected $sql;
	
	function __construct($id) {
		
		$sql = new sql();
		$sql->result("SELECT * FROM ".sql::table('server')." WHERE id = '".$id."'");
		
		$this->sql = $sql;
		
		$this->id = $this->sql->get('id');
		
	}
	
	public function createControl($data) {
		
		$id = $this->id;
		
		$dir = dir::backup("control/$id/");
		
		if(!is_dir($dir))
    		mkdir($dir);
		
		$file = fopen($dir.'control.sh', 'w');
	
		fwrite($file, $data);
		
		fclose($file);

	}
	
	public function create($array) {
		
		$id = $this->id;
		
		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$sftp = new sftp($host, $user, $pass);
		
		$sftp->makedir((string)$id);
		$sftp->chdir((string)$id);
		
		$control = games::replaceControl($this->sql->get('gameID'), $array);
		
		$this->createControl($control);
		
		$dir = dir::backup("control/$id/");
		
		$sftp->put('control.sh', $dir.'control.sh', NET_SFTP_LOCAL_FILE);
		$sftp->chmod(0777, 'control.sh');
		
		return true;
		
	}
	
	public function install() {
		
		return $this->control('install');
		
	}
	
	public function start() {
		
		return $this->control('start');
		
	}
	
	public function stop() {
		
		return $this->control('stop');
		
	}
	
	public function restart() {
		
		return $this->control('restart');
		
	}
	
	public function details() {
		
		return $this->control('details');
		
	}
	
	public static function deleteDir($id) {

		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$sftp = new sftp($host, $user, $pass);
		
		$sftp->delete((string)$id, true);
		
		$dir = dir::backup("control/$id/");
		self::deleteLocalDir($dir);
		
	}
	
	public static function deleteLocalDir($path) {
		
		if (substr($path, strlen($path) - 1, 1) != '/') {
			$path .= '/';
		}
		$files = glob($path . '*', GLOB_MARK);
		
		foreach ($files as $file) {
			
			if (is_dir($file))
				self::deleteLocalDir($file);
			else
				unlink($file);
				
		}
		rmdir($path);
	}
	
	protected function control($type) {
		
		$id = $this->id;
		
		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$ssh = new ssh($host, $user, $pass);
		
		switch($type) {
			case 'install':
			$return = $ssh->write('cd $id; ./control.sh auto-install');
			break;
			case 'start':
			$return = $ssh->write('cd $id; ./control.sh start');
			break;	
			case 'stop':
			$return = $ssh->write('cd $id; ./control.sh stop');
			break;	
			case 'restart':
			$return = $ssh->write('cd $id; ./control.sh restart');
			break;	
			case 'details':
			$return = $ssh->write('cd $id; ./control.sh details');
			break;	
		}
		
		return $return;
			
	}
	
}

?>