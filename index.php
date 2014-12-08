<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_implicit_flush(0);
mb_internal_encoding('UTF-8');
session_start();

if(version_compare(PHP_VERSION, 5.4) < 0) {
	echo 'PHP 5.4 or higher needed!';
	exit();
}

include('lib/classes/dir.php');

new dir();

include(dir::classes('autoload.php'));

autoload::register();
autoload::addDir(dir::classes('helper'));

new rp();

if(rp::get('setup') == true) {
	header('Location: install/');
	exit();
}

include(dir::functions('html.php'));
include(dir::functions('convert.php'));

lang::setDefault();
lang::setLang(rp::get('lang'));

$DB = rp::get('DB');
sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);

unset($DB);

date_default_timezone_set(rp::get('timezone', 'Europe/Berlin'));

new userLogin();
rp::add('user', new user(userLogin::getUser()));

cache::setCache(rp::get('cache'));

addonConfig::loadAllConfig();
addonConfig::includeAllLangFiles();
addonConfig::includeAllLibs();

$page = type::super('page', 'string', 'dashboard');
$action = type::super('action', 'string');
$id = type::super('id', 'int');

$success = type::get('success', 'string');
$error = type::get('error', 'string');

if(!is_null($error)) {
	echo message::danger($error);	
} elseif(!is_null($success)) {
	echo message::success($success);	
}

if(userLogin::isLogged()) {
	
	$path = 'pages/'.$page.'.php';
	
	if(file_exists($path))
		include($path);
	else
		echo message::danger(lang::get('page_not_found'), false);
	
}

$content = ob_get_contents();

ob_end_clean();

rp::add('content', $content);

if(ajax::is()) {
	echo ajax::getReturn();
	die;
}

layout::addNav(lang::get('dashboard'), 'dashboard', 'home', [], false);
layout::addNav(lang::get('settings'), 'settings', 'settings', [], false);

layout::addNav(lang::get('server'), 'server', 'list', ['add'], true);
layout::addNav(lang::get('addons'), 'addons', 'alt', [], true);
layout::addNav(lang::get('user'), 'user', 'users', ['add'], true);

if(userLogin::isLogged()) {	
	include(dir::layout('index.php', rp::get('layout')));	
} else {
	include(dir::layout('login.php', rp::get('layout')));
}

?>