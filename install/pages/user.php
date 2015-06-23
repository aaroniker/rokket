<div id="install">
	<div class="user">
        <a class="logo" href="http://rokket.info" target="_blank">
        	<?=layout::svg('rocket-panel'); ?>
        </a>
        <form action="" method="post">
        
        	<input type="hidden" name="check" value="1">
        
        	<h1><?=lang::get('user'); ?></h1>
            
            <?php
			
				if(isset($_POST['check'])) {
						
					$DB = rp::get('DB');
					sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);
					
					unset($DB);
					
					$sql = new sql();
					
					$salt = userLogin::generateSalt();
					$sql->setTable('user');
					
					$sql->addPost('firstname', type::post('firstname'));
					$sql->addPost('name', type::post('name'));
					$sql->addPost('username', type::post('username'));
					$sql->addPost('email', type::post('email'));
					$sql->addPost('password', userLogin::hash(type::post('password'), $salt));
					$sql->addPost('salt', $salt);
					$sql->addPost('admin', 1);
					
					$sql->save();
					
					rp::add('setup', false, true);
					rp::save();
										
					header('Location: ?page=finish');
					exit();
					
				}
				
			?>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('firstname'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="firstname" value="<?=type::post('firstname'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('name'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="name" value="<?=type::post('name'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('username'); ?></label>
            	<div class="col-sm-8">
            		<input type="text" name="username" value="<?=type::post('username'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('email'); ?></label>
            	<div class="col-sm-8">
            		<input type="email" name="email" value="<?=type::post('email'); ?>">
            	</div>
            </div>
        
            <div class="input row">
            	<label class="col-sm-4"><?=lang::get('password'); ?></label>
            	<div class="col-sm-8">
            		<input type="password" name="password" value="<?=type::post('password'); ?>">
            	</div>
            </div>
            
            <hr>
            
            <a href="?page=server" class="button light nospace pull-left"><?=lang::get('back'); ?></a>
            
            <button type="submit" class="nospace pull-right"><?=lang::get('finish'); ?></button>
         	
            <div class="clearfix"></div>
            
        </form>
    </div>
</div>