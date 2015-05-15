<?php include('inc/head.php'); ?>

	<div id="wrap">
    
    	<div id="control">
        	<ul>
            	<li>
					<?=layout::svg('settings'); ?>
                	<a href="?page=settings"><?=lang::get('settings'); ?></a>
                </li>
            </ul>
        </div>
    
        <div class="clear"></div>
    
        <div id="page">
            
            <section id="left">
                
                <a class="logo" href="http://rokket.info" target="_blank">
                    <?=layout::svg('rocket-panel'); ?>
                </a>
                
                <div id="user">
                	<img src="/media/user/default.png">
                	
					<div class="text"><?=rp::get('user')->get('firstname').' '.rp::get('user')->get('name'); ?></div>
                    
                    <a class="down">
                    	<?=layout::svg('down'); ?>
                    </a>
                    
                    <span class="num">0</span>
                    
                    <div class="clearfix"></div>
                    
                    <ul>
                    	<li>
                        	<a href="?page=user&action=edit&id=<?=rp::get('user')->get('id'); ?>">
                            	<?=layout::svg('users'); ?>
								<span><?=lang::get('profile'); ?></span>
                            </a>
                        </li>
                    	<li>
                        	<a href="?logout=1">
                            	<?=layout::svg('power'); ?>
								<span><?=lang::get('logout'); ?></span>
                            </a>
                        </li>
                    </ul>
                    
                </div>
                
                <h4><?=lang::get('navigation'); ?></h4>
                
                <?=layout::getNav(true); ?>
                
                <a id="expand">
                	<div class="left">
                		<?=layout::svg('left'); ?>
                    </div>
                    <div class="right">
                		<?=layout::svg('right'); ?>
                    </div>
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
    	
        <div class="clear"></div>
        
    </div>

<?php include('inc/foot.php'); ?>