<?php

class user {
	
	protected $entrys;

	public function __construct($id) {		
		
		if($id) {
			
			$sql = new sql();
			$sql->query('SELECT * FROM '.sql::table('user').' WHERE id='.$id)->result();
			
			$this->entrys = $sql->result;
			$this->entrys['perms'] = explode('|', $this->get('perms'));
			
		}
		
	}
	
	public function get($name, $default = null) {
		
		if($this->has($name)) {
			return $this->entrys[$name];
		}
		
		return $default;
		
	}
	
	public function has($name) {
	
		return isset($this->entrys[$name]) || array_key_exists($name, (array)$this->entrys);
		
	}
	
	public function getId() {
	
		return $this->get('id');
		
	}
	
	public function isAdmin() {
	
		return $this->get('admin') == 1;
		
	}
	
	public function hasPerm($perm) {
		
		if($this->isAdmin())
			return true;		
		
		return in_array($perm, (array)$this->get('perms'));
		
	}
	
	public function getAll() {
	
		return $this->entrys;
		
	}
	
}

?>