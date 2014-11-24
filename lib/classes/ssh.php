<?php

class ssh {
	
	static $host;
	static $user;
	static $password;
	
	static $ssh;
	
	static public function connect($host, $user, $password) {
	
		self::$host = $host;
		self::$user = $user;
		self::$password = $password;
			
		set_include_path(get_include_path().PATH_SEPARATOR.'lib/vendor/phpseclib');
		include('lib/vendor/phpseclib/Net/SSH2.php');
	
		self::$ssh = new Net_SSH2($host);
		
		if (!self::$ssh->login($user, $password)) {
		    echo message::danger(lang::get('ssh_login_failed'));
			return false;
		}
	
	}
	
}

?>