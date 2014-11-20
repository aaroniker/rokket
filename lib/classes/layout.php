<?php

class layout {
	
	public static $nav = [];
	
	public static $currentPage;
	
	public static function addNav($name, $link, $svg, $buttons = [], $main = true) {
		
		self::$nav[$link] = ['name'=>$name, 'buttons'=>$buttons, 'link'=>$link, 'svg'=>$svg, 'main'=>$main];
			
	}
	
	public static function getPage() {
		$page = type::super('page', 'string', 'dashboard');
		return self::$nav[$page]['name'];
	}
	
	public static function getNav() {
		$page = type::super('page', 'string', 'dashboard');
		
		$return = '<nav>';
        $return .= '<ul>';
		
		foreach(self::$nav as $var) {
			if($var['main']) {
				$active = ($var['link'] == $page) ? 'class="active"' : '';
				
				$return .= '
					<li '.$active.'>
						<a href="?page='.$var['link'].'">
							'.self::svg($var['svg']).'
						</a>
					</li>';
			}
		}
		
        $return .= '</ul>';
        $return .= '</nav>';
		
		return $return;
	}
	
	public static function getButtons() {
		
		$page = type::super('page', 'string', 'dashboard');
		$action = type::super('action', 'string');
		
		$activeA = ($action == 'add') ? 'class="active"' : '';
		$activeS = ($action == 'settings') ? 'class="active"' : '';
		
		$buttons = [
			'add'=> '<li '.$activeA.'><a href="?page='.$page.'&action=add">'.self::svg('add').'</a></li>',
			'settings'=> '<li '.$activeS.'><a href="?page='.$page.'&action=settings">'.self::svg('settings').'</a></li>'
		];
		
		$btn = self::$nav[$page]['buttons'];
		
		$return = '';
		
		if(is_array($btn) && count($btn) >= 1) {
			$return .= '<nav>';
			$return .= '<ul>';
			foreach($btn as $var) {
				$return .= $buttons[$var];
			}
			$return .= '</ul>';
			$return .= '</nav>';
		}
		
		return $return;
		
	}
	
	public static function svg($icon) {
		
		ob_start();
			include(dir::layout('icons/'.$icon.'.svg', rp::get('layout')));
			$return = ob_get_contents();
		ob_end_clean();
		
		return $return;
		
	}
	
}

?>