<?php
use dflydev\markdown\MarkdownParser;

class markdown {
	
	public static function parse($content) {
		
		return (new MarkdownParser())->transformMarkdown($content);
		
	}
	
}

?>