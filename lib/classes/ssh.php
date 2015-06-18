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
		
		$this->ssh = new Net_SSH2($host);
		
		if(!$this->ssh->login($user, $pass)) {
		    echo message::danger(lang::get('ssh_login_failed'), false);
			return false;
		}
		
		return $this->ssh;
	
	}
	
	public function exec($cmd) {
		
		return $this->ssh->exec($cmd);
		
	}
	
	public function login($user, $pass) {
		
		return $this->ssh->login($user, $pass);
	
	}
	
	public function write($cmd) {
		
		return $this->ssh->write($cmd);
	
	}
	
	public function read($preg, $param) {
		
		return $this->ssh->read($preg, $param);
			
	}
	
	public function addScreen($name) {
		
		return $this->ssh->exec('screen -mdS '.$name);
			
	}
	
	public function getScreen() {
		
		return $this->ssh->exec('screen -ls');
			
	}
	
	public function getLog() {
		
		return $this->ssh->getLog();
			
	}
	
	public function isTimeout() {
		
		return $this->ssh->isTimeout();
			
	}
	
	public function setTimeout($time) {
		
		return $this->ssh->setTimeout($time);
			
	}
	
	public function reset() {
		
		return $this->ssh->reset();
			
	}
	
}

?>