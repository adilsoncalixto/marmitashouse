<?php

namespace App\widgets\form;

class Button
{
	private $content;
	private $attributes;
	private $button;
	
	public function setContent(string $content) {
		$this->content = $content;
	}
	
	public function setAttributes(string $propertie, string $value) {
		$this->attributes[$propertie] = $value;
	}
	
	public function show() {
		$this->button = "<button class=\"btn btn-primary\"";		
		foreach($this->attributes as $attribute => $value) {
			$this->button .= " {$attribute}=\"{$value}\"";
		}
		$this->button .= "> {$this->content} </button> &nbsp;";
		unset($this->content);
		$this->attributes = array();
		return $this->button;
	}
}