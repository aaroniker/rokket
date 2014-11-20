<?php

class message {
	
	static protected function getMessage($message, $close, $class) {
		
		$return = '<div class="message '.$class.'">';
		
		if($close) {
			
		}
		
		$return .= $message;
		
		$return .= '</div>';
		
		return $return;
	}

	static public function warning($message, $close = false) {
		
		return self::getMessage($message, $close, 'warning');
		
	}
	
	static public function info($message, $close = false) {
	
	   return self::getMessage($message, $close, 'info');
	   
	}
	
	static public function danger($message, $close = false) {
	
		return self::getMessage($message, $close, 'error');
	
	}
	
	static public function success($message, $close = false) {
	
		return self::getMessage($message, $close, 'success');
	
	}

}

?>
