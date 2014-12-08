<?php

class sftp {
	
	protected $host;
	protected $user;
	protected $pass;
	
	protected $sftp;
	
	public function __construct($host, $user, $pass) {
	
		$this->host = $host;
		$this->user = $user;
		$this->password = $pass;
			
		set_include_path(get_include_path().PATH_SEPARATOR.'lib/vendor/phpseclib');
		include('lib/vendor/phpseclib/Net/SFTP.php');
	
		$this->sftp = new Net_SFTP($host);
		
		if (!$this->sftp->login($user, $pass)) {
		    echo message::danger(lang::get('sftp_login_failed'), false);
			return false;
		}
		
		return $this->sftp;
	
	}
	
	public function put($cmd) {
		
		return $this->sftp->put($cmd);
		
	}
	
	public function mkdir($dir) {
		
		return $this->sftp->mkdir($dir);
		
	}
	
	public function chdir($dir) {
		
		return $this->sftp->chdir($dir);
		
	}
	
	public function rmdir($dir) {
		
		return $this->sftp->rmdir($dir);
		
	}
	
}

?>