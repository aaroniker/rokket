<?php

class message {
	
	static protected function getMessage($message, $close, $class) {
		
		$close = ($close) ? ' close' : ''; 
		
		$return = '<div class="message '.$class.$close.'">';
		
		$return .= $message;
		
		$return .= '</div>';
		
		return $return;
	}

	static public function warning($message, $close = true) {
		
		return self::getMessage($message, $close, 'warning');
		
	}
	
	static public function info($message, $close = true) {
	
	   return self::getMessage($message, $close, 'info');
	   
	}
	
	static public function danger($message, $close = true) {
	
		return self::getMessage($message, $close, 'error');
	
	}
	
	static public function success($message, $close = true) {
	
		return self::getMessage($message, $close, 'success');
	
	}

}

?>
