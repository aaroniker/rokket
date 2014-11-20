<?php include('inc/head.php'); ?>

		<div id="wrap">
    
    	<div id="control">
        	<ul>
            	<li>
					<?=layout::svg('home'); ?>
                    <a href="?page=dashboard"><?=lang::get('dashboard'); ?></a>
                </li>
            	<li>
					<?=layout::svg('settings'); ?>
                	<a href="?page=settings"><?=lang::get('settings'); ?></a>
                </li>
            </ul>
        </div>
    
        <div class="clear"></div>
    
        <div id="page">
            
            <section id="left">
                
                <a class="logo" href="/">
                    <?=layout::svg('rocket-panel'); ?>
                </a>
                
                <div id="user">
                	<img src="/media/user/aaron-iker.png">
                    <span>2</span>
                    <?=layout::svg('more'); ?>
                </div>
                
                <?=layout::getNav(); ?>
                
                <a href="?logout=1" id="logout">
                	<?=layout::svg('power'); ?>
                </a>
                
            </section>
            
            <section id="main">
            
                <div id="head">
                
                    <h1><?=layout::getPage(); ?></h1>
                    
                    <?=layout::getButtons(); ?>
                    
                    <div id="search">
                        <?=layout::svg('search'); ?>
                        <form action="" method="post">
                            <input type="text" placeholder="<?=lang::get('search'); ?>" name="q">
                        </form>
                    </div>
                    
                    <div class="clear"></div>
                
                </div>
                
                <div id="content">
					<?=rp::get('content'); ?>
        		</div>
                
            </section>
            
        </div>
    
    </div>

<?php include('inc/foot.php'); ?>