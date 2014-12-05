<?php

	$SSH = rp::get('SSH');
	
	$host = new host($SSH['ip'], $SSH['user'], $SSH['password']);
	
	unset($SSH);
	
?>

<div class="row">
	
    <div class="col-md-3 col-sm-6">
    	<div class="stats">
            <?php
            	$ram = $host->ram();
			?>
        	<div class="text">
            	<h3>RAM</h3>
                <?=sprintf(lang::get('ram_total'), $ram['total'].' MB'); ?>
            </div>
            
            <div class="circle" data-color="31343B">
        	    <div><?=$ram['percentage']; ?>%</div>
        	    <svg data-color="f5f6f9"></svg>
        	</div>
        
        </div>
    </div>
	
    <div class="col-md-3 col-sm-6">
    	<div class="stats primary">
            <?php
            	$cpu = $host->cpu();
			?>
        	<div class="text">
            	<h3>CPU</h3>
                <?=sprintf(lang::get('cpu_average'), $cpu['num']); ?>
            </div>
            
            <div class="circle" data-color="da6765">
        	    <div><?=$cpu['load']; ?>%</div>
        	    <svg data-color="ffffff"></svg>
        	</div>
        
        </div>
    </div>
	
    <div class="col-md-3 col-sm-6">
    	<div class="stats">
            <?php
            	$hdd = $host->hdd();
				$numHdd = count($hdd);
				
				$percentHdd = 0;
				$total = 0;
				
				foreach($hdd as $disk) {
					$percentHdd = $percentHdd + $disk['percentage'];
					$total = $total + toByte($disk['total']);
				}
				$average = $percentHdd / $numHdd;
			?>
        	<div class="text">
            	<h3>HDD</h3>
                <?=sprintf(lang::get('hdd_avail'), byteToSize($total)); ?>
            </div>
            
            <div class="circle" data-color="31343B">
        	    <div><?=$average; ?>%</div>
        	    <svg data-color="f5f6f9"></svg>
        	</div>
        
        </div>
    </div>
	
    <div class="col-md-3 col-sm-6">
    	<div class="stats">
        	<div class="text">
            	<h3>UPTIME</h3>
                <?=lang::get('since_start'); ?>
            </div>
            <div class="right">
            	<?=$host->uptime(true); ?>
            </div>
        </div>
    </div>
    
</div>

<div class="row">

    <div class="col-md-8">
    
        <div class="panel">
            <div class="top">
                <h3><?=lang::get('server_info'); ?></h3>
            </div>
            <div class="content">
            	<?php
				
					$table = new table(['class'=>['horizontal']]);
					
					$table->addCollsLayout('40%, *');
					
					$table->addSection('tbody');
					
					$table->addRow()
						->addCell(lang::get('distribution'), ['class'=>'first'])
						->addCell($host->distribution());
						
					$table->addRow()
						->addCell(lang::get('firmware'), ['class'=>'first'])
						->addCell($host->firmware());
					
					$table->addRow()
						->addCell(lang::get('hostname'), ['class'=>'first'])
						->addCell($host->hostname());
						
					$table->addRow()
						->addCell(lang::get('kernel'), ['class'=>'first'])
						->addCell($host->kernel());
					
					$ethernet = $host->ethernet();
					
					$table->addRow()
						->addCell(lang::get('ethernet'), ['class'=>'first'])
						->addCell('Up: '.byteToSize($ethernet['up']).' / Down: '.byteToSize($ethernet['down']));
						
					echo $table->show();
				?>
            </div>
        </div>
    
    </div>
    
    <div class="col-md-4">
    
    	<h2><?=lang::get('rokket_about'); ?></h2>
        
        <p><?=lang::get('rokket_text'); ?></p>
    
        <a href="https://www.facebook.com/pages/Rokket/1562909277254179" class="button facebook full svg" target="_blank"><?=layout::svg('facebook'); ?>Facebook</a>
        <a href="https://github.com/callofsorrow/rokket" class="button github full svg" target="_blank"><?=layout::svg('github'); ?>GitHub</a>
        <a href="http://rokket.info" class="button none full" target="_blank"><?=lang::get('visit_website'); ?></a>
    
    </div>

</div>