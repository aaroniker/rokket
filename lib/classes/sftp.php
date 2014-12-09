<?php

class sftp {
	
	protected $host;
	protected $user;
	protected $pass;
	
	protected $sftp;
	
	public function __construct($host, $user, $pass) {
			
		set_include_path(get_include_path().PATH_SEPARATOR.'lib/vendor/phpseclib');
		include('lib/vendor/phpseclib/Net/SFTP.php');

		$this->sftp = new Net_SFTP($host);
		
		if (!$this->sftp->login($user, $pass)) {
			echo message::danger(lang::get('sftp_login_failed'), false);
		}
	
	}
	
	public function put($cmd) {
		
		return $this->sftp->put($cmd);
		
	}
	
	public function makedir($dir) {
		
		return $this->sftp->mkdir($dir);
		
	}
	
	public function createDir($dir, $name){
	
		$this->sftp->chdir($dir);
		
		$this->sftp->mkdir($name);
	
	}
	
	public function chdir($dir) {
		
		return $this->sftp->chdir($dir);
		
	}
	
	public function rmdir($dir) {
		
		return $this->sftp->rmdir($dir);
		
	}
	
	public function ls($dir = '', $nlist = false) {
		
		if($nlist)
			return $this->sftp->nlist($dir);
		
		return $this->sftp->rawlist($dir);
		
	}
	
	public function info($file) {
		
		return $this->sftp->stat($file);
		
	}
	
	public function delete($file, $dir = false) {
		
		return $this->sftp->delete($file, $dir);
		
	}
	
	public function rename($old, $new) {
		
		return $this->sftp->rename($old, $new);
		
	}
	
}

?>