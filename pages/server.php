<?php
	if($action == 'add') {
?>

<div class="panel">
	<div class="top">
		<h3><?=lang::get('server_add'); ?></h3>
	</div>
	<div class="content">
    
    	<form action="" method="post">
    
        <div class="row">
                        
            <div class="col-md-6">
            	<div class="input row">
            		<label class="col-sm-5"><?=lang::get('name'); ?></label>
            		<div class="col-sm-7">
            			<input type="text" name="name">
            		</div>
            	</div>
                
            	<div class="input row">
            		<label class="col-sm-5"><?=lang::get('game'); ?></label>
                    <div class="col-sm-7">
                        
                        <select name="gameID">
                        <option value="<?=rp::get('lang'); ?>"><?=lang::get('game_select'); ?></option>
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
            
        <a href="?page=user" class="light button"><?=lang::get('back'); ?></a>
        <button type="submit" name="send"><?=lang::get('add'); ?></button>
        
        </form>
        
    </div>
</div>

<?php
	} elseif($action == 'edit' && $id) {
?>
	
<?php	
	} else {
		
	$table = new table();
		
	$table->addCollsLayout('25, 32%, *, 70, 100');
	
	$table->addRow()
		->addCell("
			<input type='checkbox' id='all'>
			<label for='all'></label>
		", ['class'=>'checkbox'])
		->addCell(lang::get('name'))
		->addCell(lang::get('game'))
		->addCell(lang::get('port'))
		->addCell("");
	
	$table->addSection('tbody');
	
	$table->setSql('SELECT * FROM '.sql::table('server'));
	
	if($table->numSql()) {
	
		while($table->isNext()) {
			
			$id = $table->get('id');
			
			$edit = '<a class="btn" href="?page=server&action=edit&id='.$id.'">'.layout::svg('edit').'</a>';
			
			$table->addRow()
				->addCell("
					<input type='checkbox' id='id".$id."'>
					<label for='id".$id."'></label>
				", ['class'=>'checkbox'])
				->addCell($table->get('name'))
				->addCell($table->get('gameID'))
				->addCell($table->get('port'))
				->addCell($edit);
			
			$table->next();
		
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>5, 'class'=>'first']);
		
	}
?>

<div class="row">

    <div class="col-md-8">
    
        <div class="panel">
            <div class="top">
                <h3><?=$table->numSql().' '.lang::get('server'); ?></h3>
                <ul>
                    <li>
                        <a href=""><?=layout::svg('delete'); ?></a>
                    </li>
                </ul>
            </div>
            <?=$table->show(); ?>
        </div>
    
    </div>
    
    <div class="col-md-4">
    	<?php
		
			$table = new table();
				
			$table->addCollsLayout('*, 80');
			
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

<?php } ?>