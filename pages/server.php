<div class="row">

    <div class="col-md-8">
    
        <div class="panel">
            <div class="top">
                <h3>Headline</h3>
                <ul>
                    <li>
                        <a href="">
                        <svg version="1.1" x="0px" y="0px" viewBox="0 0 64 64">
                            <g enable-background="new">
                                <g>
                                    <g>
                                        <path d="M12.938,42.498l8.446,8.429l25.339-25.288l-8.447-8.429L12.938,42.498z M7.998,55.84l11.258-2.806l-8.447-8.431
                                            L7.998,55.84z M55.167,12.996l-4.224-4.215c-1.166-1.164-3.057-1.164-4.223,0l-6.334,6.322l8.446,8.429l6.334-6.322
                                            C56.333,16.046,56.333,14.159,55.167,12.996z"/>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 64 64">
                                <g enable-background="new">
                                    <g>
                                        <g>
                                            <path d="M53,9H38V6c0-1.657-1.343-3-3-3h-6c-1.657,0-3,1.343-3,3v3H11c-1.657,0-3,1.343-3,3v3h48v-3C56,10.343,54.657,9,53,9z
                                                 M11,58c0,1.657,1.343,3,3,3h36c1.657,0,3-1.343,3-3V18H11V58z M41,27c0-1.657,1.343-3,3-3s3,1.343,3,3v25c0,1.657-1.343,3-3,3
                                                s-3-1.343-3-3V27z M29,27c0-1.657,1.343-3,3-3s3,1.343,3,3v25c0,1.657-1.343,3-3,3s-3-1.343-3-3V27z M17,27c0-1.657,1.343-3,3-3
                                                s3,1.343,3,3v25c0,1.657-1.343,3-3,3s-3-1.343-3-3V27z"/>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <table>
                <thead>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="all">
                            <label for="all"></label>
                        </td>
                        <td width="30%">Name</td>
                        <td width="*">Description</td>
                        <td width="140">Created</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="id1">
                            <label for="id1"></label>
                        </td>
                        <td><strong>Server #1</strong></td>
                        <td>Lorem ipsum dolor set amet, nunc dir</td>
                        <td>
                            14 minutes ago
                        </td>
                    </tr>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="id2">
                            <label for="id2"></label>
                        </td>
                        <td><strong>Server #2</strong></td>
                        <td>Lorem ipsum dolor set amet, nunc dir</td>
                        <td>
                            1 day ago
                        </td>
                    </tr>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="id3">
                            <label for="id3"></label>
                        </td>
                        <td><strong>Server #3</strong></td>
                        <td>Lorem ipsum dolor set amet, nunc dir</td>
                        <td>
                            2 days ago
                        </td>
                    </tr>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="id4">
                            <label for="id4"></label>
                        </td>
                        <td><strong>Server #4</strong></td>
                        <td>Lorem ipsum dolor set amet, nunc dir</td>
                        <td>
                            4 weeks ago
                        </td>
                    </tr>
                    <tr>
                        <td width="25" class="checkbox">
                            <input type="checkbox" id="id5">
                            <label for="id5"></label>
                        </td>
                        <td><strong>Server #5</strong></td>
                        <td>Lorem ipsum dolor set amet, nunc dir</td>
                        <td>
                            1 year ago
                        </td>
                    </tr>
                </tbody>
            </table>
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