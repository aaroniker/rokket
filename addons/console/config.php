<?php
	
	layout::addNav(lang::get('console'), 'console', 'terminal', [], true);
	
	layout::addCSS('addons/console/layout/css/console.css');
	
	if($page == 'console') {
		function consolePath() {
			return 'addons/console/console.php';	
		}
		extension::add('PAGE_PATH', 'consolePath');
		
		layout::addJS('addons/console/layout/js/console.js');
	}
	
?>