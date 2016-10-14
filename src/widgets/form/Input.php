<?php

namespace App\widgets\form;

/**
 * Configura e exibe a tag HTML input
 * @author Jorge Lucas
 */
class Input
{
	/**
	 * @var string $type Tipo do campo input (text, date, password etc)
	 * @var array $label Nome do label e o id associado 
	 * @var array $attributes Atributos e valores da tag
	 * @var string $input A própria tag html
	 */
	private $type;
	private $label;
	private $attributes;
	private $input;
	
	/**
	 * Armazena o tipo do campo input
	 * @param string $type TTipo do campo input (text, date, password etc)
	 * @return void
	 */
	public function setType(string $type) {
		$this->type = $type;
	}
	
	/**
	 * Armazena o id do input e o texto da label que será associado
	 * @param string $inputId Id do elemento html
	 * @param string $label Texto descritivo
	 * @return void
	 */
	public function setLabel(string $inputId, string $label) {
		$this->label[] = $inputId;
		$this->label[] = $label;
	}	
	
	/**
	 * Armazena os atributos do elemento
	 * @param string $propertie Nome da propriedade
	 * @param string $value Valor
	 * @return void
	 */
	public function setAttributes(string $propertie, string $value) {
		$this->attributes[$propertie] = $value; 
	}
	
	/**
	 * Configura e exibe o elemento input
	 * @return string Elemento configurado
	 */
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