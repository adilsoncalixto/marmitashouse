<?php

namespace App\widgets\form;

/**
 * Classe geradora de elementos html select
 * @author Jorge Lucas
 */
class Select
{
	/**
	 * @var array $options Nomes e seus respectivos valores
	 * @var string $name Atributo name do elemento
	 * @var string $id Atributo id do elemento
	 * @var array $label Tag label que acompanha o elemento
	 */
	private $options;
	private $name;
	private $id;
	private $label;
	
	/**
	 * Recebe o atributo name do elemento em sua instanciação
	 * @param string $name
	 * @return void
	 */
	public function __construct(string $name) {
		$this->name = $name;
	}
	
	/**
	 * Configura o atributo id do elemento
	 * @param string $id default null 
	 * @return void
	 */
	public function setId(string $id = null) {
		$this->id = $id;
	}
	
	/**
	 * Configura aa tag label associada
	 * @param string $label
	 * @return void
	 */
	public function setLabel(string $id, string $label) {
		$this->label[0] = $id;
		$this->label[1] = $label;
	}
	
	/**
	 * Recebe os valores e opções que irão compor o elemento
	 * @param array $options
	 * @return void
	 */
	public function setOptions(array $options) {
		$this->options = $options;
	}

	/**
	 * Monta o elemento e exibe-o
	 * @return string $select Elemento montado
	 */
	public function show() {
		$select = "<label for=\"{$this->label[0]}\" class='control-label'> {$this->label[1]} </label>\n";
		$select .= "<select name='{$this->name}' id='{$this->id}' class='form-control'>\n";		
		foreach ($this->options as $value => $text) {
			$select .= "<option value='{$value}'>{$text}</option>";
		}
		$select .= "</select>\n";
		return $select;
	}
}