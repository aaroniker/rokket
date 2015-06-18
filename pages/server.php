<?php
	
	if($action == 'games') {
?>

<div class="row">

    <div class="col-md-12">
    	<?php
		
			$table = new table();
				
			$table->addCollsLayout('*, 20%');
			
			$table->addRow()
				->addCell(lang::get('name'), ['class' => 'first'])
				->addCell(lang::get('id'));
			
			$table->addSection('tbody');
			
			foreach(games::getAll() as $game)
				
				$table->addRow()
					->addCell($game['name'], ['class' => 'first'])
					->addCell($game['id']);
		?>
		
		<div class="panel">
			<div class="top">
				<h3><?=lang::get('games'); ?></h3>
			</div>
			<?=$table->show(); ?>
		</div>
        
    </div>

</div>

<?php
	} elseif($action == 'add') {
		
		if(ajax::is()) {
			
			if(type::post('id')) {
			
				$config = games::getConfig(type::post('id'));
				
				$return = '';
				
				foreach($config['vars'] as $key => $var) {
					$return .= '<div class="input row">';
					$return .= '<label class="col-sm-5">'.$var['name'].'</label>';
					$return .= '<div class="col-sm-7">';
					
					if(strpos($var['type'], ',') !== false) {
						$values = explode(',', $var['type']);
						$return .= '<select name="var['.$key.']">';
						foreach($values as $option)
							$return .= '<option value="'.$option.'">'.$option.'</option>';
						$return .= '</select>';
					} else {
						$return .= '<input type="text" name="var['.$key.']">';
					}
					
					$return .= '</div>';
					$return .= '</div>';
				}
			
			} else
				$return = lang::get('choose_game_first');
			
			ajax::addReturn($return);
		}
	
?>

<div class="panel">
	<div class="top">
		<h3><?=lang::get('server_add'); ?></h3>
	</div>
	<div class="content">
    
    	<form action="?page=server" method="post">
    
        <div class="row">
                        
            <div class="col-md-7">
            
            	<div class="input row">
            		<label class="col-sm-5"><?=lang::get('name'); ?></label>
            		<div class="col-sm-7">
            			<input type="text" name="name">
            		</div>
            	</div>
            
            	<div class="input row">
            		<label class="col-sm-5"><?=lang::get('port'); ?></label>
            		<div class="col-sm-7">
            			<input type="text" name="var[port]" maxlength="5">
            		</div>
            	</div>
                
            	<div class="input row">
            		<label class="col-sm-5"><?=lang::get('game'); ?></label>
                    <div class="col-sm-7">
                        
                        <select name="gameID">
                        <option value=""><?=lang::get('game_select'); ?></option>
                        <?php
                            $games = games::getAll();
                            
                            foreach($games as $var)
                                echo '<option value="'.$var['id'].'">'.$var['name'].'</option>';
                            
                        ?>
                        </select>
                    </div>
            	</div>
                
            </div>
        
        </div>
        
        <hr>
        
        <h2><?=lang::get('variables'); ?></h2>
    
        <div class="row">
                        
            <div class="col-md-7">
        
        		<div id="ajax"><?=lang::get('choose_game_first'); ?></div>
                
            </div>
        
        </div>
                
        <hr>
            
        <a href="?page=server" class="light button"><?=lang::get('back'); ?></a>
        <button type="submit" name="sendNew"><?=lang::get('add'); ?></button>
        
        </form>
        
    </div>
</div>
	
<?php	
	} else {
		
	if(isset($_POST['sendNew'])) {	
	
		$new = new sql();
		$new->setTable('server');
		
		$vars = type::post('var');
		
		$new->addPost('gameID', type::post('gameID'));
		$new->addPost('name', type::post('name'));
		$new->addPost('port', $vars['port']);
		$new->addPost('status', '');
		
		$new->save();
		
		$newID = $new->insertId();
		
		$vars['id'] = type::post('gameID').$newID;
		
		$server = new server($newID);
		$server->create((array)$vars);
		
		echo message::success(lang::get('server_added'));
	
	}
		
	if(isset($_POST['delete'])) {	
		
		$ids = type::post('ids');
		
		if(is_array($ids) && count($ids) >= 1) {
			
			foreach($ids as $var) {
				$sql = new sql();
				$sql->setTable('server');
				$sql->setWhere("id=".$var);
				$sql->delete();
				
				server::deleteDir($var);
			}
			
			echo message::success(lang::get('server_deleted'));
		
		} else
		
			echo message::danger(lang::get('choose_server'));
		
	}
		
	if($action == 'install' && $id) {
		$server = new server($id);
		$server->install();
		echo message::success(lang::get('server_install'));
	}
		
	if(ajax::is()) {
		
		if($action == 'start' && $id) {
			$server = new server($id);
			$server->start();
			$return = message::success(lang::get('server_started'));
		}
		
		if($action == 'stop' && $id) {
			$server = new server($id);
			$server->stop();
			$return = message::success(lang::get('server_stopped'));
		}
		
		ajax::addReturn($return);
		
	}
	
	$table = new table();
		
	$table->addCollsLayout('25, 32%, *, 70, 190');
	
	$table->addRow()
		->addCell("
			<input type='checkbox' id='all'>
			<label for='all'></label>
		", ['class'=>'checkbox'])
		->addCell(lang::get('name'))
		->addCell(lang::get('game'))
		->addCell(lang::get('port'))
		->addCell(lang::get('status'));
	
	$table->addSection('tbody');
	
	$table->setSql('SELECT * FROM '.sql::table('server'));
	
	if($table->numSql()) {
	
		while($table->isNext()) {
			
			$id = $table->get('id');
			
			$server = new server($id);
			
			$status = $server->status($table->get('status'));
			
			$table->addRow()
				->addCell("
					<input type='checkbox' name='ids[]' value='".$id."' id='id".$id."'>
					<label for='id".$id."'></label>
				", ['class'=>'checkbox'])
				->addCell($table->get('name'))
				->addCell($table->get('gameID'))
				->addCell($table->get('port'))
				->addCell($status, ['class'=>'toggleState']);
			
			$table->next();
		
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>5, 'class'=>'first']);
		
	}
	
?>

<div class="row">

    <div class="col-md-12">
    
        <div class="panel">
        	<form action="" method="post">
            <div class="top">
                <h3><?=$table->numSql().' '.lang::get('server'); ?></h3>
                <ul>
                    <li>
                        <button type="submit" name="delete"><?=layout::svg('delete'); ?></button>
                    </li>
                </ul>
            </div>
            <?=$table->show(); ?>
            </form>
        </div>
    
    </div>
    
</div>

<?php } ?>