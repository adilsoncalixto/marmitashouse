<?php

namespace App\widgets\form;

/**
 * Configura e exibe uma tag HTML button
 * @author Jorge Lucas
 */
class Button
{
	/**
	 * @var string $content Conteúdo do botão
	 * @var array $attributes Atributos para adicionar a tag
	 * @var string $button Conteúdo HTML do botão
	 */
	private $content;
	private $attributes;
	private $button;
	
	/**
	 * Armazena o concteúdo do botão (Geralmente texto)
	 * @param string $content Texto
	 * @return void
	 */
	public function setContent(string $content) {
		$this->content = $content;
	}
	
	/**
	 * Armazena os atributos e valores no array $attributes
	 * @param string $propertie Nome da propriedade html
	 * @param string $value Valor da propriedade
	 * @return void
	 */
	public function setAttributes(string $propertie, string $value) {
		$this->attributes[$propertie] = $value;
	}
	
	/**
	 * Configura e exibe o button HTML
	 * @return string Botão configurado e pronto
	 */
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