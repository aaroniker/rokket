<div id="install">
	<div class="lang">
        <a class="logo" href="http://rokket.info" target="_blank">
        	<?=layout::svg('rocket-panel'); ?>
        </a>
        <nav>
        	<ul>
        	<?php
				foreach(lang::getLangs() as $key => $lang) {
					echo '<li><a href="?page=database&lang='.$key.'">'.$lang.'</a></li>';	
				}
        	?>
            </ul>
        </nav>
    </div>
</div>