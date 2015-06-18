<?php

class apiserver {
	
	const BLOG = 'http://rokket.info/api/blog.json';
	
	public static function getFile($file) {
	
		$ch = curl_init($file);
		curl_setopt($ch, CURLOPT_PORT, 80);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla (Statuscheck-Script)');
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl = curl_exec($ch);
		curl_close($ch);
		
		return $curl;
		
	}
	
	public static function getBlogFile() {
		
		return json_decode(self::getFile(self::BLOG), true);
		
	}
	
}

?>