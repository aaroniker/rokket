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
	
?>

<div class="row">

	<div class="col-md-7">
    
    </div>
	
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