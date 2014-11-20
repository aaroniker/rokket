<?php

class table {
	
	protected $thead = [];
	protected $tfoot = [];
	protected $tbody = [];
	
	protected $current_section;
	
	protected $tableAttr = [];	
	protected $collsLayout = [];
	protected $caption = [];
	
	var $sql;
	var $isSql = false;
	
	
	function __construct($attributes = []) {
		
		$this->addSection('thead');
		
		$attributes['class'][] = 'table';
		
		$this->tableAttr = $attributes;
	}
	
	public function addSection($section, $attributes = []) {
		
		if(in_array($section, ['thead', 'tfoot'])) {
			
			$this->current_section = $section;
			
		} else {
			
			$this->current_section = 'tbody';
			
		}
		
		$ref = $this->getCurrentSection();
	
		$ref['attr'] = $attributes;
		$ref['rows'] = [];
		
		$this->setCurrentSection($ref);
		
		return $this;
		
	}
	
	protected function getCurrentSection() {
		
		if($this->current_section === 'thead') {
			return $this->thead;	
		} elseif($this->current_section === 'tfoot') {
			return $this->tfoot;	
		} else {
			return $this->tbody;	
		}
	}
	
	protected function setCurrentSection($section) {
		
		if($this->current_section === 'thead') {
			$this->thead = $section;	
		} elseif($this->current_section === 'tfoot') {
			$this->tfoot = $section;	
		} else {
			$this->tbody = $section;	
		}
		
		return $this;
	
	}
	
	public function addCollsLayout($cols) {
	
		if(!is_array($cols)) {			
			
			$col2 = explode(',', $cols);
			$cols = [];
			
			foreach($col2 as $key=>$val) {			
				$cols[]['width'] = $val;			
			}			
			
		}
		
		$this->collsLayout = $cols;
		
		return $this;
		
	}
	
	protected function getCollsLayout() {
		
		$cols = '';
		foreach($this->collsLayout as $val) {
			$cols .= $this->addTag('col', $val, '', false);
		}
		
		return $this->addTag('colgroup', '', $cols);
	}

	protected function addTag($name, $attributes = [], $value = '', $end = true) {
		
		$attributes = $this->convertAttr($attributes);
		
		$endtag = ($end) ? '</'.$name.'>' : '';
		
		return '<'.$name.$attributes.'>'.$value.$endtag.PHP_EOL;		
		
	}
	
	public function addCaption($value, $attributes = []) {	
		
		$this->caption = ['value'=>$value, 'attr'=>$attributes];
		
		return $this;
		
	}
	
	protected function getCaption() {
		
		if(count($this->caption)) {
			return $this->addTag('caption', $this->caption['attr'], $this->caption['value']);
		}
		
		return '';
		
	}
	
	protected function convertAttr($attributes) {
		
		if(!count($attributes) || is_string($attributes))
			return '';
		
		
		return html_convertAttribute($attributes);
		
	}
	
	public function setSql($query) {
	
		$this->sql = new sql();
		$this->sql->query($query)->result();
		
		$this->isSql = true;
		
		return $this;
		
	}
	
	public function numSql() {
		
		return $this->sql->num();
		
	}
	
	public function isNext() {
	
		return $this->sql->isNext();
		
	}
	
	public function get($value) {
	
		return $this->sql->get($value);
		
	}
	
	public function next() {
	
		$this->sql->next();
		
		return $this;
		
	}
	
	public function getSql() {
		
		return $this->sql;
			
	}
	
	public function addRow($attributes = []) {
		
		$ref = $this->getCurrentSection();
		
		$ref['rows'][] = [
			'attr' => $attributes,
			'cells' => []
		];
		
		$this->setCurrentSection($ref);
		
		return $this;
		
	}
	
	public function addCell($value = '', $attributes = []) {
		
		$type = ($this->current_section === 'thead') ? 'th' : 'td';
		
		$cell = [
			'value' => $value,
			'type' => $type,
			'attr' => $attributes
		];
		
		$ref = $this->getCurrentSection();
		
		$count = count( $ref['rows'] );
		$ref['rows'][$count-1]['cells'][] = $cell;
		
		$this->setCurrentSection($ref);
		
		return $this;
		
	}
	
	public function addCells($values, $attributes = []) {
	
		if(!is_array($values)) return $this;
		
		foreach($values as $value) {
		
			$this->addCell($value, $attributes);
			
		}
		
		return $this;
		
	}
	
	protected function getRowCells($cells) {
		
		$str = '';
		
		foreach( $cells as $cell ) {
			$str .= $this->addTag($cell['type'], $cell['attr'], $cell['value']);
		}
		
		return $str;
		
	}
	
	protected function getSection($sec, $tag) {
		
		if(!count($sec))
			return '';	
	
		$str = '';	
		foreach($sec['rows'] as $row) {
			$str .= $this->addTag('tr', $row['attr'], $this->getRowCells($row['cells']));
		}		
		
		return $this->addTag($tag, $sec['attr'], $str);
		
	}
	
	public function show() {
				
		$return = $this->getCaption();
		
		$return .= $this->getCollsLayout();
		$return .= $this->getSection($this->thead, 'thead');
		$return .= $this->getSection($this->tfoot, 'tfoot');		
		$return .= $this->getSection($this->tbody , 'tbody');				
		
		$return =  $this->addTag('table', $this->tableAttr, PHP_EOL.$return);
		
		return $return;
		
	}
	
}

?>