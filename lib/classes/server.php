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
		
		$sftp->makedir("functions");
		$sftp->chdir("functions");
		
		$functions = ['fn_install', 'fn_install_complete', 'fn_install_config'];
		
		foreach($functions as $file) {
			$path = dir::functions('control/'.$file);	
			$sftp->put($file, $path, NET_SFTP_LOCAL_FILE);
			$sftp->chmod(0777, $file);
		}
		
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
	
	public function status($status) {
		
		$id = $this->id;
		
		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$sftp = new sftp($host, $user, $pass);
		$sftp->chdir((string)$id);
		
		$ssh = new ssh($host, $user, $pass);
		
		if(is_array($sftp->nlist()) && in_array('status.txt', $sftp->nlist())) {
			if($ssh->exec("cd $id; cat status.txt") == 0)
				return '
					<div class="installLoad">
						<svg version="1.1" x="0px" y="0px" viewBox="0 0 40 40" enable-background="new 0 0 40 40">
							<path opacity="0.2" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
							  s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
							  c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
							<path d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
							  C22.32,8.481,24.301,9.057,26.013,10.047z">
							  <animateTransform attributeType="xml"
								attributeName="transform"
								type="rotate"
								from="0 20 20"
								to="360 20 20"
								dur="1s"
								repeatCount="indefinite"/>
							</path>
						</svg>
						'.lang::get('server_installing').'
					</div>
				';
			else {
				if($status == 1) {
					$state = lang::get('online');
					$checked = 'checked="checked"';
				} else {
					$state = lang::get('offline');
					$checked = '';
				}
				
				return '
					<div class="switch">
                    	<input name="running[]" id="server'.$id.'" value="'.$id.'" type="checkbox" '.$checked.'>
                    	<label for="server'.$id.'"></label>
                    	<div>'.$state.'</div>
                	</div>
				';
			}
		} else
			return '<a href="?page=server&id='.$id.'&action=install">'.lang::get('server_not_installed').'</a>';
		
	}
	
	protected function control($type) {
		
		$id = $this->id;
		
		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$ssh = new ssh($host, $user, $pass);
		
		$sql = new sql();
		$sql->setTable('server');
		$sql->setWhere('id='.$id);
		
		#$ssh->read('[prompt]');
		
		switch($type) {
			case 'install':
			#$ssh->exec("cd $id; ./control.sh auto-install >> ~/debug.log 2>&1 & /n");
			$ssh->exec("cd $id; ./control.sh auto-install >> /dev/null 2>&1 & /n");
			$sql->addPost('status', 0);
			break;
			case 'start':
			$ssh->exec("cd $id; ./control.sh start >> /dev/null 2>&1 & /n");
			$sql->addPost('status', 1);
			break;
			case 'stop':
			$ssh->exec("cd $id; ./control.sh stop >> /dev/null 2>&1 & /n");
			$sql->addPost('status', 0);
			break;
			case 'restart':
			$ssh->exec("cd $id; ./control.sh restart >> /dev/null 2>&1 & /n");
			$sql->addPost('status', 1);
			break;
		}
		
		$sql->update();
			
	}
	
}

?>