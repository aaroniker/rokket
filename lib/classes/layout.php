<?php

class layout {
	
	public static $nav = [];
	public static $jsFiles = [];
	public static $cssFiles = [];
	
	public static $currentPage;
	
	public static function addNav($name, $link, $svg, $buttons = [], $main = true) {
		
		self::$nav[$link] = ['name'=>$name, 'buttons'=>$buttons, 'link'=>$link, 'svg'=>$svg, 'main'=>$main];
			
	}
	
	public static function addJs($js_file, $attributes = []) {
	
		$attributes['src'] = $js_file;
	
		self::$jsFiles[] = $attributes;
	
	}
	
	public static function addCss($css_file, $media = 'screen', $attributes = []) {
	
		if(!isset($attributes['rel']))
			$attributes['rel'] = 'stylesheet';
	
		$attributes['href'] = $css_file;
		$attributes['media'] = $media;
		
		self::$cssFiles[] = $attributes;
	
	}
	
	public static function getCSS() {
	
		$return = '';
		
		foreach(self::$cssFiles as $css) {
			$return .= '<link'.self::convertAttr($css).'>'.PHP_EOL;
		}
		
		return $return;
	
	}
	
	public static function getJS() {
	
		$return = '';
	
		foreach(self::$jsFiles as $css) {
			$return .= '<script'.self::convertAttr($css).'></script>'.PHP_EOL;
		}
	
		return $return;
	
	}
	
	public static function getPage() {
		$page = type::super('page', 'string', 'dashboard');
		
		return (isset(self::$nav[$page]['name'])) ? self::$nav[$page]['name'] : '';
	}
	
	public static function getNav($full = false) {
		$page = type::super('page', 'string', 'dashboard');
		
		$return = '<nav>';
        $return .= '<ul>';
		
		foreach(self::$nav as $var) {
			
			$span = ($full) ? '<span>'.$var['name'].'</span>' : '';
			
			if($var['main']) {
				$active = ($var['link'] == $page) ? 'class="active"' : '';
				
				$return .= '
					<li '.$active.'>
						<a href="?page='.$var['link'].'">
							'.self::svg($var['svg']).'
							'.$span.'
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
		$activeG = ($action == 'games') ? 'class="active"' : '';
		$activeR = '';
		$activeS = ($action == 'settings') ? 'class="active"' : '';
		
		$buttons = [
			'add'=> '<li '.$activeA.'><a href="?page='.$page.'&action=add">'.self::svg('add').'</a></li>',
			'games'=> '<li '.$activeG.'><a href="?page='.$page.'&action=games">'.self::svg('game').'</a></li>',
			'refresh'=> '<li '.$activeR.'><a>'.self::svg('refresh').'</a></li>',
			'settings'=> '<li '.$activeS.'><a href="?page='.$page.'&action=settings">'.self::svg('settings').'</a></li>'
		];
			
		$return = '';
		
		if(isset(self::$nav[$page])) {
		
			$btn = self::$nav[$page]['buttons'];
			
			if(is_array($btn) && count($btn) >= 1) {
				$return .= '<nav>';
				$return .= '<ul>';
				foreach($btn as $var) {
					$return .= $buttons[$var];
				}
				$return .= '</ul>';
				$return .= '</nav>';
			}
		
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
	
	protected static function convertAttr($attr) {
		return html_convertAttribute($attr);
	}
	
}

?>