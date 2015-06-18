<div id="install">
	<div class="database">
        <a class="logo" href="http://rokket.info" target="_blank">
        	<?=layout::svg('rocket-panel'); ?>
        </a>
        <form action="" method="post">
        
        	<input type="hidden" name="check" value="1">
        
        	<h1><?=lang::get('database'); ?></h1>
            
            <?php
			
				if(isset($_POST['check'])) {
					
					$host = type::post('host');
					$user = type::post('user');
					$password = type::post('password');
					$database = type::post('database');
					$prefix = type::post('prefix');
					
					ob_start();
					
					$sqlCheck = sql::connect($host, $user, $password, $database);
					
					$error = ob_get_contents();
					ob_end_clean();
					
					if($sqlCheck)
						echo message::danger(lang::get('db_not_correct').' - <small>'.lang::get('show_errors').'</small>', false);	
					else {
						
						$DB = [
							'host' => $host,
							'user' => $user,
							'password' => $password,
							'database' => $database,
							'prefix' => $prefix
						];
						
						rp::add('DB', $DB, true);
						rp::save();
						
						header('Location: ?page=server');
						exit();
					}
					
				}
				
			?>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('host'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="host" value="<?=type::post('host'); ?>" placeholder="localhost">
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
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('database'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="database" value="<?=type::post('database'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('prefix'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="prefix" value="<?=type::post('prefix'); ?>">
            	</div>
            </div>
            
            <hr>
            
            <a href="?page=lang" class="button light nospace pull-left"><?=lang::get('back'); ?></a>
            
            <button type="submit" class="nospace pull-right"><?=lang::get('save'); ?></button>
         	
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>