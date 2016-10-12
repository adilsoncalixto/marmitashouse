<?php

namespace App\widgets\base;

/**
 * Classe geradora de elementos HTML
 * @author Jorge Lucas
 */
class Element
{
	private $tag;
	private $attributes;
	private $content;
	private $element;
	
	/**
	 * Construtor da classe
	 * @param string $tag Elemento HTML
	 * @return void
	 */
	public function __construct(string $tag)
	{
		$this->tag = $tag;
	}
	
	/**
	 * Modifica a tag HTML em uso
	 * @param string $tag
	 * @return void
	 */
	public function changeTag(string $tag)
	{
		$this->tag = $tag;
	}
	
	/**
	 * Atribuidor de atributos HTML (propriedade e valor)
	 * @param string $propertie
	 * @param string $value
	 * @return void
	 */
	public function setAttribute(string $propertie, string $value)
	{
		$this->attributes[$propertie] = $value;
	}
	
	/**
	 * Atribui um texto para ser inserido dentro da tag HTML
	 * @param mixed $text
	 * @return void
	 */
	public function setContent(string $content)
	{
		$this->content = $content;
	}
	
	/**
	 * Quando chamado, finaliza o elemento com seus atributos
	 * @close bool Determina se deve-se fechar um tag
	 * @return void
	 */
	public function show()
	{
		$this->element = "<{$this->tag}";
	
		if($this->attributes) {
			foreach($this->attributes as $attribute => $value) {
				$this->element .= " {$attribute}=\"{$value}\"";
			}
		}
	
		if(!empty($this->content)) {
			$this->element .= ">\n";
			$this->element .= $this->content . "\n";
			$this->element .= "</{$this->tag}>\n";
		} else {
			$this->element .= ">\n";
		}
	
		$this->attributes = array();
		unset($this->content);
		return $this->element;
	}
	
	/**
	 * Fecha a tag HTML
	 * @return string
	 */
	public function closeTag()
	{
		$tag = $this->tag;
		$this->tag = "";
		return "</{$tag}>\n";
	}
}
