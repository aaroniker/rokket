<?php

class userLogin {

	static protected $isLogin = false;
	static protected $userID = null;
	
	const SALT_LENGTH = 6;
	const HASH_TYPE = 'sha256';
	
	public function __construct() {
	
		if(!is_null(type::get('logout', 'string')) && is_null(type::post('login', 'string'))) {
	
			self::logout();
			   
		} elseif(self::checkLogin()) {
	
			self::loginSession();
	
		} elseif (!is_null(type::post('login', 'string'))) {
	
			self::loginPost();
	
		}
	
	}
	
	protected static function loginSession() {
	
		self::$isLogin = true;
	
	}
	
	protected static function checkLogin() {
	
		$session = type::session('login', 'int', 0);
		$cookie = type::cookie('remember', 'int');
		
		if(!$session && !$cookie)
			return false;
		
		self::loginSession();
		self::$userID = ($session) ? $session : $cookie;
		
		return true;		
		
	}
	
	protected static function loginPost() {
		
		$email = type::post('email', 'string');
		$password = type::post('password', 'string');
		$remember = type::post('remember', 'int');
		
		if(is_null($email) || is_null($password) || $email == '' || $password == '') {
			
			echo message::info(lang::get('fill_out_both'));
			return;
			
		}
		
		$sql = new sql();
		$sql->query('SELECT password, salt, id FROM '.sql::table('user').' WHERE `email` = "'.$sql->escape($email).'"');
		
		if(!$sql->num()) {
		
			echo message::danger(sprintf(lang::get('email_not_found'), htmlspecialchars($email)), true);
			$shake = 1;
			return;
			
		}
				
		$sql->result();
		
		if(!self::checkPassword($password, $sql->get('salt'), $sql->get('password'))) {
			
			echo message::danger(lang::get('wrong_pw'));
			$shake = 1;
			return;
			
		}
		
		self::loginSession();
		self::$userID = $sql->get('id');
		
		$_SESSION['login'] = $sql->get('id');
		
		if($remember)
			setcookie("remember", $sql->get('id'), time() + 3600 * 24 * 7);
	
	}
	
	public static function hash($password, $salt) {
		
		if(empty($salt)) {
			return sha1($password);	
		}
		
		return hash(self::HASH_TYPE, $salt.$password.$salt);
		
	}
	
	public static function checkPassword($password, $salt, $hash) {
	
		return self::hash($password, $salt) == $hash;
	
	}
	
	public static function generateSalt() {
		
		$allowed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$lenght = strlen($allowed)-1;
		$str = '';
		for($i = 0; $i < self::SALT_LENGTH; $i++) {
				$str .= $allowed[rand(0, $lenght)];
		}
		
		return $str;
		
	}

	public static function logout() {   
	
		unset($_SESSION['login']);
		self::$isLogin = false;
		setcookie("remember", "", time() - 3600);
		echo message::info(lang::get('logged_out'), true);
		
	}
	
	public static function isLogged() {
		
		return self::$isLogin;
		
	}
	
	public static function getUser() {
		
		return self::$userID;
		
	}
	

}

?>
