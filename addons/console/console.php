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
				
				if(ajax::is()) 
					$test = 1;
				else {
					echo nl2br($ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX));
					$ssh->write("screen -r test123 \n\r");
					
					$ssh->setTimeout(4);
					$ssh->write("ping google.de \n\r");
				}
				
				if(ajax::is()) {
					
                	#$ssh->setTimeout(1);
					#$ssh->write("ping google.de \n\r");
                	$ssh->setTimeout(4);
					$ssh->exec("screen -r test123");
                	#$return = nl2br($ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX));
					
					ajax::addReturn($return);	
				
				}
                
            ?>
        </div>
    </div>
</div>