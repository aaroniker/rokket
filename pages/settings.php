<?php
	
	if($action == 'deleteCache') {
		cache::clear();
		echo message::success(lang::get('delete_cache_success'));	
	}
	
	if($action == 'generateCSS') {
		$less = new lessc;
		
		$error = false;
		$newCSS = '';
		
		try {
			
			$newCSS = $less->compileFile(dir::layout('less/style.less', rp::get('layout')));
			
			$fp = fopen(dir::layout('css/style.css', rp::get('layout')),"wb");
			fwrite($fp, $newCSS);
			fclose($fp);
			
		} catch (exception $e) {
			echo message::danger($e->getMessage());
			$error = true;
		}
		
		if(!$error)
			echo message::success(lang::get('generate_css_success'));	
	}
	
	if(isset($_POST['send'])) {
		
		rp::add('lang', type::post('lang', 'string'), true);
		rp::add('logs', type::post('logs', 'int'), true);
		rp::add('ip', type::post('ip', 'string'), true);
		rp::add('email', type::post('email', 'string'), true);
		rp::add('emailNot', type::post('emailNot', 'int'), true);
		
		rp::save();
			
		echo message::success(lang::get('settings_edited'));	
	}
	
?>

<div class="row">

    <form action="" method="post">
	
    <div class="col-md-7">
    
    	<div class="panel">
        	<div class="top">
            	<h3><?=lang::get('general'); ?></h3>
            </div>
            <div class="content">
            
                <div class="input row">
                    <label class="col-sm-4"><?=lang::get('language'); ?></label>
                    <div class="col-sm-8">
                        
                        <select name="lang">
                        <option value="<?=rp::get('lang'); ?>"><?=lang::get('lang_select'); ?></option>
                        <?php
                            $handle = opendir(dir::base('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR));
                            
                            while($file = readdir($handle)) {
                            
                                if(in_array($file, ['.', '..']))
                                    continue;
                            
                                echo '<option value="'.$file.'">'.$file.'</option>';
                            }
                        ?>
                        </select>
                    </div>
                    
                </div>
            
                <div class="input row">
                    <label class="col-sm-4"><?=lang::get('logs'); ?></label>
                    <div class="col-sm-8">
    
                        <div class="switch">
                            <input name="logs" id="logs" value="1" type="checkbox" <?=(rp::get('logs')) ? 'checked="checked"' : ''; ?>>
                            <label for="logs"></label>
                            <div><?=lang::get('yes'); ?></div>
                        </div>
                        
                    </div>
                </div>
            
                <div class="input row">
                    <label class="col-sm-4"><?=lang::get('ip'); ?></label>
                    <div class="col-sm-8">
                        <input type="text" name="ip" value="<?=rp::get('ip'); ?>">
                    </div>
                </div>
                
            </div>
        </div>
        
        <div class="panel">
        	<div class="top">
            	<h3><?=lang::get('notifications'); ?></h3>
            </div>
            <div class="content">
            
                <div class="input row">
                    <label class="col-sm-4"><?=lang::get('email_notifcation'); ?></label>
                    <div class="col-sm-8">
    
                        <div class="switch">
                            <input name="emailNot" id="emailNot" value="1" type="checkbox" <?=(rp::get('emailNot')) ? 'checked="checked"' : ''; ?>>
                            <label for="emailNot"></label>
                            <div><?=lang::get('yes'); ?></div>
                        </div>
                        
                    </div>
                </div>
            
                <div class="input row">
                    <label class="col-sm-4"><?=lang::get('email'); ?></label>
                    <div class="col-sm-8">
                        <input type="text" name="email" value="<?=rp::get('email'); ?>">
                    </div>
                </div>
                
            </div>
        </div>
        
        <button type="submit" class="mbt15" name="send"><?=lang::get('apply'); ?></button>
    
    </div>
                
    </form>
	
    <div class="col-md-5">
    	
        <div class="panel">
            <div class="top">
                <h3><?=lang::get('cache'); ?></h3>
            </div>
            <div class="content">
            
            	<div class="row bt10">
                	<div class="col-xs-5">
                    	<label>style.less</label>
                    </div>
                    <div class="col-xs-7">
                    	<a href="?page=settings&action=generateCSS" class="button full"><?=lang::get('gen_css'); ?></a>
                    </div>
                </div>
                
            	<div class="row">
                	<div class="col-xs-5">
                    	<label><?=lang::get('cache'); ?></label>
                    </div>
                    <div class="col-xs-7">
                    	<a href="?page=settings&action=deleteCache" class="button full"><?=lang::get('clear_all'); ?></a>
                    </div>
                </div>
            
            </div>
        </div>
        
    </div>
    
</div>