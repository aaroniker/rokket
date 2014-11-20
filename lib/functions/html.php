<?php


function html_convertAttribute($attributes) {
	
	$return = '';
		
	foreach($attributes as $key=>$val) {
			
		if(is_int($key)) {
				
			$return .= ' '.$val;
				
		} else {
				
			if(is_array($val)) {
				$val = implode(' ', $val);	
			}
			
			$return .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($val).'"';	
			
		}			
		
	}
		
	return $return;
	
}

function getDeleteModal($title, $content) {
	if($content == '') {
		$content = lang::get('really_delete');	
	}
	
	if($title == '') {
		$title = lang::get('really_delete');	
	}
	
?>
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><?= $title ?></h3>
            </div>
            <div class="modal-body"><?= $content; ?></div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-sm btn-default confirm"><?= lang::get('delete') ?></button>
                <button type="button" class="btn btn-sm btn-warning" data-dismiss="modal"><?= lang::get('close') ?></button>
            </div>
        </div>
    </div>
</div>
<?php	
}

function bootstrap_panel($title, $content, $buttons = false) {
	
	$class = '';
	$clearfix = '';
	
	try {
	
		if($buttons !== false) {
			
			if(!is_array($buttons) && dyn::get('debug')) {
				throw new InvalidArgumentException('$buttons must be an array');
			}
			
			$class = ' pull-left';
			$clearfix = '<div class="clearfix"></div>';
			$buttons = '<div class="btn-group pull-right">'.PHP_EOL.implode(PHP_EOL, (array)$buttons).'</div>';
		}
	
	} catch(InvalidArgumentException $e) {
		echo message::warning($e->getMessage());
	}
	
	echo '<div class="row">
        <div class="col-lg-12">
        	<div id="ajax-content"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title'.$class.'">'.$title.'</h3>
                   '.$buttons.'
				   '.$clearfix.'                  
                </div>
                '.$content.'
            </div>
        </div>
    </div>';
	
}


?>