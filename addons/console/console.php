<div class="panel">
	<div class="content">
        <div id="terminal">
            <?php
                
                $SSH = rp::get('SSH');
                    
                $host = $SSH['ip'];
                $user = $SSH['user'];
                $pass = $SSH['password'];
                
                unset($SSH);
                
                $ssh = new ssh($host, $user, $pass);
                
                echo nl2br($ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX));
					
				if(ajax::is()) {
					
                	$ssh->setTimeout(1);
					$ssh->write("ping google.de \n\r");
                
                	$return = nl2br($ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX));
					
					ajax::addReturn($return);	
				
				}
                
            ?>
        </div>
    </div>
</div>