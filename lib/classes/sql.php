<?php

class sql {

	static $DB_host;
	static $DB_user;
	static $DB_password;
	static $DB_datenbank;
	
	static $QUERY_TYPE = [MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH];
	
	public $query;
	public $result;
	
	var $counter = 0;
	
	static $sql;
	
	public $values = [];
	public $table;
	public $where;
	
	static public function connect($host, $user, $pw, $db) {
	
		self::$DB_host = $host;
		self::$DB_user = $user;
		self::$DB_password = $pw;
		self::$DB_datenbank = $db;
		
		try {
			self::$sql = new mysqli(self::$DB_host, self::$DB_user, self::$DB_password, self::$DB_datenbank);
			
			$sql = new sql();
			$sql->query('SET SQL_MODE=""');
			
			self::$sql->set_charset('utf8');
			
			if(self::$sql->connect_error && rp::get('debug')) {
				throw new MySQLi_Sql_Exception('<b>Fehler mit der Verbindung zum Server:</b><br />'.self::$sql->connect_error);
			}
			
		} catch(MySQLi_Sql_Exception $e) {
			echo message::danger($e->getMessage());	
		}
		
		return self::$sql->connect_error;
	
	}
	
	static public function table($table) {
		
		$DB = rp::get('DB');
		
		return $DB['prefix'].$table;
		
	}
	
	public function query($query) {
		
		$this->query = self::$sql->query($query);
			
		try {
			
			if(!$this->query) {
				throw new Exception($query.'<br />'.self::$sql->error);
			}
			
		} catch(Exception $e) {
			
			echo message::danger($e->getMessage());
			
		}
		
		return $this;
		
	}
	
	public function result($query = false, $type = MYSQL_ASSOC) {
		
		
		try {
			
			if($query) {
				$this->query($query);
			}
			
			if(!in_array($type, self::$QUERY_TYPE)) {
				
				throw new Exception(sprintf(lang::get('sql_result_invalid_type'), __CLASS__));
				
			}	
			
			$this->result = $this->query->fetch_array($type);
			
		} catch(Exception $e) {
			
			echo message::danger($e->getMessage());
			
		}
		
		return $this;		
		
	}
	
	public function num($query = false) {
		
		if(!$query) {
			return ($this->query) ? $this->query->num_rows : 0;			
		}
			
		$sql = new sql();
		$sql->result($query);
		return $sql->num();
	
	}
	
	public function insertId() {
		
		return self::$sql->insert_id;
		
	}
	
	public function get($row, $default = null) {
		
		if(isset($this->values[$row])) {
		
			return $this->values[$row];
			
		}
		
		return $this->getValue($row, $default);
		
	}
	
	public function getArray($row, $delimiter= '|') {
	
		return explode($delimiter, $this->get($row));
		
	}
	
	public function getJson($row) {
	
		return json_decode($this->get($row), true);
		
	}
	
	public function getSerialize($row) {
	
		return unserialize($row);
		
	}
	
	public function getValue($row, $default = null) {
		
		if(isset($this->result[$row])) {
			
			return 	$this->result[$row];
			
		}
		
		return $default;
		
	}
	
	public function getRow() {
		
		return $this->result;
			
	}
	
	public static function showColums($table, $prefix = '', $like = true) {
		
		$suffix = '';
		if($like) {
			$suffix = '%';	
		}
		
		if($prefix) {
			$prefix	= ' LIKE "'.$prefix.$suffix.'"';
		}	
			
		$class = __CLASS__;
		$sql = new $class();
		$sql->result('SHOW COLUMNS FROM '.sql::table($table).$prefix);
		
		return $sql;
	}
	 
	public function next() {		
		
		$this->counter++;
		
		if($this->isNext()) {
			
			$this->result();
			
		}
		
		return $this;
		
	}
	
	public function isNext() {
		
		return $this->counter < $this->num();
		
	}
	
	public function getPosts($post) {
	
		if(!is_array($post) && rp::get('debug')) {
		
			throw new InvalidArgumentException(__CLASS__.'::'.__METHOD__.' ertwartet als 1. Parameter ein array');
			
		}
		
		foreach($post as $val=>$cast) {
			$this->values[$val] = $this->escape(type::post($val, $cast, '')); 	
		}
		
		return $this;
			
	}
	
	public function addPost($name, $val) {
		
		$this->values[$name] = $this->escape($val);
		
		return $this;
		
	}
	
	public function addDatePost($name, $val = 'now') {
	
		$date = new DateTime($val);
		
		return $this->addPost($name, $date->format('Y-m-d H:i:s'));
		
	}
	
	public function delPost($name) {
	
		unset($this->values[$name]);
		
		return $this;
		
	}
	
	public function getPost($name, $default = null) {
	
		if(isset($this->values[$name])) {
		
			return $this->values[$name];	
			
		}
		
		return $default;
		
	}
	
	public function escape($name) {
	
		return self::$sql->escape_string($name);
		
	}
	
	public function setWhere($where) {
	
		$this->where = $where;
		
		return $this;
		
	}
	
	public function setTable($table) {
		
		$this->table = self::table($table);
		
		return $this;
		
	}
	
	public function select($select = '*') {
		
		$this->query('SELECT '.$select.' FROM `'.$this->table.'` WHERE '.$this->where);
		
		return $this;
		
	}
	
	public function save() {
		
		$keys = '`'.implode('`,`', array_keys($this->values)).'`';
		$entrys = '"'.implode('","', $this->values).'"';
		
		$this->query('INSERT INTO `'.$this->table.'` ('.$keys.') VALUES ('.$entrys.')');
		
		return $this;
		
	}
	
	public function update() {
		
		$entrys = '';
		
		foreach($this->values as $key=>$val) {
			$entrys .= ' `'.$key.'` = "'.$val.'",';
		}
		
		$entrys = substr($entrys , 0, -1);		
		
		$this->query('UPDATE `'.$this->table.'` SET'.$entrys.' WHERE '.$this->where);
		
		return $this;
		
	}
	
	public function delete() {
	
		$this->query('DELETE FROM `'.$this->table.'` WHERE '.$this->where);
		
		return $this;
		
	}
	
	static public function sortTable($table, $sort, $where = '', $select = ['id', 'sort']) {
		
		if($where)
			$where = ' WHERE '.$where;
		
		$update = new sql();
		$update->setTable($table);
		
		$i = 1;
		
		$sql = new sql();
		$sql->query('SELECT `'.$select[0].'`, `'.$select[1].'` FROM '.self::table($table).$where.' ORDER BY `'.$select[1].'` ASC')->result();
		
		while($sql->isNext()) {
			
			if($sort == $i) {
				$i++;	
			}
			
			$update->addPost($select[1], $i);
			
			$update->setWhere($select[0].'='.$sql->get($select[0]));
			$update->update();
			
			$sql->next();
			$i++;
			
		}
		
	}

}

?>