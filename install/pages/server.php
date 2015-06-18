<div id="install">
	<div class="database">
        <a class="logo" href="http://rokket.info" target="_blank">
        	<?=layout::svg('rocket-panel'); ?>
        </a>
        <form action="" method="post">
        
        	<input type="hidden" name="check" value="1">
        
        	<h1><?=lang::get('server'); ?></h1>
            
            <?php
			
				if(isset($_POST['check'])) {
					
					$ip = type::post('ip');
					$user = type::post('user');
					$password = type::post('password');
					
					ob_start();
					$ssh = new ssh($ip, $user, $password);
					
					$error = ob_get_contents();
					ob_end_clean();
					
					if($error)
						echo $error;	
					else {
						
						$SSH = [
							'ip' => $ip,
							'user' => $user,
							'password' => $password
						];
						
						rp::add('SSH', $SSH, true);
						rp::save();
						
						header('Location: ?page=user');
						exit();
					}
					
				}
				
			?>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('ip'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="ip" value="<?=type::post('ip'); ?>" placeholder="IP:Port">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('user'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="user" value="<?=type::post('user'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('password'); ?></label>
            	<div class="col-sm-8">
            		<input type="password" name="password" value="<?=type::post('password'); ?>">
            	</div>
            </div>
            
            <hr>
            
            <a href="?page=database" class="button light nospace pull-left"><?=lang::get('back'); ?></a>
            
            <button type="submit" class="nospace pull-right"><?=lang::get('save'); ?></button>
         	
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>