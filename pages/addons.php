<?php

$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

if($action == 'delete') {
	
	$addonClass = new addon($addon, false);
	echo $addonClass->delete();
}

if($action == 'install') {

	$addonClass= new addon($addon);	
	
	$success = true;
	
	if(!$addonClass->isInstall()) {
		if(!$addonClass->install()) {
			$success = false;
		}
	} else {
		$addonClass->uninstall();	
	}
	
	if($success) {
		
		$install = ($addonClass->isInstall()) ? 0 : 1;
	
		$sql = new sql();
		$sql->setTable('addons');
		$sql->setWhere('`name` = "'.$addon.'"');
		$sql->addPost('install', $install);
		
		if(!$install)
			$sql->addPost('active', 0);
		
		$sql->update();
		
		echo message::success(lang::get('addon_save_success'));
	}
	
}

if($action == 'active') {
	
	$addonClass = new addon($addon, false);	
	$active = ($addonClass->isActive()) ? 0 : 1;
	
	if(!$addonClass->isInstall()) {
		
		echo message::danger(sprintf(lang::get('addon_install_first'), $addon));
			
	} else {
	
		$sql = new sql();
		$sql->setTable('addons');
		$sql->setWhere('`name` = "'.$addon.'"');
		$sql->addPost('active', $active);
		$sql->update();
		
		echo message::success(lang::get('addon_save_success'));
		
	}
	
}

if($action == 'help') {
	$curAddon = new addon($addon);
?>
	<div class="panel">
		<div class="top">
			<h3><?php echo $curAddon->get('name'); ?></h3>
        </div>
        <div class="content">           
			<?php
                $file = dir::addon($addon, 'README.md');
                if(file_exists($file)) {
                    echo markdown::parse(file_get_contents($file));
                } else {
                    echo lang::get('addon_no_readme');	
                }
            ?>
		</div>
    </div>
<?php	
} else {
	
	$table = new table();
	$table->addCollsLayout('25,*,215, 70');
	
	$table->addRow()
	->addCell('')
	->addCell(lang::get('name'))
	->addCell(lang::get('status'))
	->addCell('');
	
	$table->addSection('tbody');
	
	$addons = array_diff(scandir(dir::base('addons'.DIRECTORY_SEPARATOR)), ['.', '..', '.htaccess']);

	if(count($addons)) {
	
		foreach($addons as $dir) {
			
			$curAddon = new addon($dir);
			$status = '';
					
			$delete = '<a href="?page=addons&addon='.$dir.'&action=delete" class="delete"></a>';
			
			$status .= '
				<form action="" method="get">
					<input type="hidden" name="page" value="addons">
					<input type="hidden" name="addon" value="'.$dir.'">
					<select class="addonAction" name="action">';
					
					if($curAddon->isActive())
						$status .= '<option selected="selected" value="">'.lang::get('addon_actived').'</option>';
					elseif($curAddon->isInstall())
						$status .= '<option selected="selected" value="">'.lang::get('addon_installed').'</option>';
					else
						$status .= '<option selected="selected" value="">'.lang::get('addon_not_installed').'</option>';
					
					if($curAddon->isInstall()) {
						$status .= '<option value="install">'.lang::get('uninstall').'</option>';
					} else {
						$status .= '<option value="install">'.lang::get('install').'</option>';
					}
					
					if($curAddon->isActive()) {
						$status .= '<option value="active">'.lang::get('deactivate').'</option>';
					} else {
						$status .= '<option value="active">'.lang::get('activate').'</option>';
					}
			
					$status .= '<option value="delete">'.lang::get('delete').'</option>';
					
			$status .= '</select>
				</form>
			';
			
			$table->addRow()
			->addCell('')
			->addCell($curAddon->get('name').' <small>'.$curAddon->get('version').'</small>')
			->addCell('<span class="btn-group">'.$status.'</span>')
			->addCell('<a class="help" href="?page=addons&addon='.$dir.'&action=help">?</a>');
				
		}
	
	} else {
	
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>3, 'class'=>'first']);
		
	}
	
	?>
    <div class="panel">
		<div class="top">
			<h3><?=count($addons).' '.lang::get('addons'); ?></h3>
        </div>
        <?=$table->show(); ?>
    </div>
<?php
}
?>