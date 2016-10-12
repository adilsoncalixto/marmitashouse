<?php

namespace App\widgets\form;

class Input
{
	private $type;
	private $label;
	private $attributes;
	private $input;
	
	public function setType(string $type) {
		$this->type = $type;
	}
	
	public function setLabel(string $inputId, string $label) {
		$this->label[] = $inputId;
		$this->label[] = $label;
	}	
	
	public function setAttributes(string $propertie, string $value) {
		$this->attributes[$propertie] = $value; 
	}
	
	public function show() {
		$this->input = "<label for=\"{$this->label[0]}\"> {$this->label[1]} </label>\n"; 
		$this->input .= "<div class=\"input-group\">\n";
		$this->input .= "<input class=\"form-control\" type=\"{$this->type}\"";
		
		foreach($this->attributes as $attribute => $value) {
			$this->input .= " {$attribute}=\"{$value}\"";
		}
		
		$this->input .= " >\n</div>";		
		$this->attributes = array();
		$this->label = array();
		$this->type = null;
		return $this->input;
	}
}