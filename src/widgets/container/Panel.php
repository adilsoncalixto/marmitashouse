<?php

namespace App\widgets\container;

/**
 * Configura e exibe paineis para comportar dados
 * @author Jorge Lucas
 */
class Panel
{
	/**
	 * @var string header Título do painel
	 * @var string Conteúdo HTML do painel
	 */
	private $header;
	private $content;
	
	/**
	 * Armazena a string passada e armazena para ser o título
	 * @param string $text
	 * @return void
	 */
	public function __construct(string $text) {
		$this->header = $text;	
	}
	
	/**
	 * Amrmazena o conteúdo a ser inserido no painel
	 * @param mixed $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 * Configura e exibe o painel
	 * @return string $panel
	 */
	public function show() {
		$panel = <<<PANEL
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<h3 class='panel-title'> $this->header </h3>
			</div>
			<div class='panel-body'>
				$this->content
			</div>
		</div>
PANEL;
		
		echo $panel;
	}
	
}