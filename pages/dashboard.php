<?php

	$SSH = rp::get('SSH');
	
	$host = new host($SSH['ip'], $SSH['user'], $SSH['password']);
	
	unset($SSH);
	
?>