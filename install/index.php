<?php

	ob_start();

	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	
	include('../lib/classes/dir.php');
	
	new dir('../');
	
	include(dir::classes('autoload.php'));
	
	autoload::register();
	autoload::addDir(dir::classes('helper'));

	set_include_path(get_include_path().PATH_SEPARATOR.'../lib/vendor/phpseclib');
	include_once('Net/SSH2.php');
	include_once('Net/SFTP.php');
	
	define('NET_SSH2_LOGGING', NET_SSH2_LOG_COMPLEX);
	
	new rp();
				
	include(dir::functions('html.php'));
	include(dir::functions('convert.php'));

	lang::setDefault();
	lang::setLang(rp::get('lang'));

	$page = type::super('page', 'string', 'lang');
	$action = type::super('action', 'string');
	$lang = type::super('lang', 'string');
	$id = type::super('id', 'int');
	
	if($lang) {
		lang::setLang($lang);
		rp::add('lang', $lang, true);
		rp::save();
	}
	
	$success = type::get('success', 'string');
	$error = type::get('error', 'string');
	
	if(!is_null($error)) {
		echo message::danger($error);	
	} elseif(!is_null($success)) {
		echo message::success($success);	
	}
	
	$path = 'pages/'.$page.'.php';
	
	if(file_exists($path))
		include($path);
	else
		echo message::danger(lang::get('page_not_found'), false);
	
	$content = ob_get_contents();

	ob_end_clean();
	
	layout::addNav(lang::get('choose_lang'), 'lang', '', [], true);
	layout::addNav(lang::get('database'), 'database', '', [], true);
	layout::addNav(lang::get('server'), 'server', '', [], true);
	layout::addNav(lang::get('user'), 'user', '', [], true);
	layout::addNav(lang::get('finish'), 'finish', '', [], true);
	
	rp::add('content', $content);
	
	include('inc/head.php');
	
	echo rp::get('content');
	
	include('inc/foot.php');
?>