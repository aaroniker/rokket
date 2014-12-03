<?php

class ssh {
	
	protected $host;
	protected $user;
	protected $pass;
	
	protected $ssh;
	
	public function __construct($host, $user, $pass) {
	
		$this->host = $host;
		$this->user = $user;
		$this->password = $pass;
			
		set_include_path(get_include_path().PATH_SEPARATOR.'lib/vendor/phpseclib');
		include('lib/vendor/phpseclib/Net/SSH2.php');
	
		$this->ssh = new Net_SSH2($host);
		
		if (!$this->ssh->login($user, $pass)) {
		    echo message::danger(lang::get('ssh_login_failed'), false);
			return false;
		}
		
		return $this->ssh;
	
	}
	
	public function exec($cmd) {
		
		return $this->ssh->exec($cmd);
		
	}
	
}

?>