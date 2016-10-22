<?php

namespace App\widgets\form;

class Radio
{
	private $name;
	private $attributes;
	
	public function __construct(string $name) {
		$this->name = $name;
	}
	
	public function setAttributes(string $id, string $value, string $text) {
		$this->attributes[] = [$id, $value, $text];
	}
	
	public function show() {
		
		$radio = "<div class='radio'>";
		foreach($this->attributes as $matrix) {
			$radio .= <<<RADIO
					<label class='radio-inline'>
						<input type='radio' name='{$this->name}' id='{$matrix[0]}' value='{$matrix[1]}'> {$matrix[2]}
					</label>
RADIO;
		}
		$radio .= "</div>";
		return $radio;
	}
}