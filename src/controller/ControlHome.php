<?php

namespace App\controller;

use App\widgets\base\Element;

/**
 * Gerencia as requisições para a página Home do sistema
 * @author Jorge Lucas
 */
class ControlHome
{
	/**
	 * Exibe o conteúdo da página Home
	 * @return void
	 */
	public function show() {
		
		$div = new Element('div');
		$div->setAttribute("style", "width: 350px; margin: auto;");
			
		$h2 = new Element('h2');
		$h2->setAttribute('align', 'center');
		$h2->setContent('<br><br>Bem vindo!');
			
		$div->setContent($h2->show());
		echo $div->show();
	}
}