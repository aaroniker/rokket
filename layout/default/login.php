<?php include('inc/head.php'); ?>

	<div id="login" class="animated <?=(!is_null(type::post('login', 'string'))) ? 'shake' : ''; ?>">
    
        <a class="logo" href="/">
        	<?=layout::svg('rocket-panel'); ?>
        </a>
        
        <?=rp::get('content'); ?>
        
        <form action="" method="post">
        	
            <input type="email" name="email" placeholder="<?=lang::get('email'); ?>" value="<?=type::post('email'); ?>">
            
            <input type="password" name="password" placeholder="<?=lang::get('password'); ?>">
            
            <div class="foot">
    
                <div class="switch">
                    <input name="remember" id="remember" value="1" type="checkbox" <?=(type::post('remember', 'int')) ? 'checked="checked"' : ''; ?>>
                    <label for="remember"></label>
                    <div><?=lang::get('remember_login'); ?></div>
                </div>
                
                <button name="login" type="submit">
                    <?=layout::svg('check'); ?>
                </button>
            
            </div>
        
        </form>
    
    </div>

<?php include('inc/foot.php'); ?>
