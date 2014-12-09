<?php

class server {

	protected $id;
	
	protected $sql;
	
	public $gameID;
	
	function __construct($id) {
		
		$sql = new sql();
		$sql->result("SELECT * FROM ".sql::table('server')." WHERE id = '".$id."'");
		
		$this->sql = $sql;
		
	}
	
	public function create() {

		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$sftp = new sftp($host, $user, $pass);
		
		$sftp->makedir((string)$this->sql->get('id'));
	}
	
	public static function deleteDir($id) {

		$SSH = rp::get('SSH');
		
		$host = $SSH['ip'];
		$user = $SSH['user'];
		$pass = $SSH['password'];
		
		unset($SSH);
		
		$sftp = new sftp($host, $user, $pass);
		
		$sftp->delete((string)$id, true);
	}
	
}

?>