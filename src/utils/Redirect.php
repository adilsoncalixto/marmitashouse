<?php

namespace App\utils;

/**
 * Classe faz o redirecionameto das páginas
 * @author Jorge Lucas
 */
final class Redirect
{
	//Caminho alvo do redirecionameto
	private $url;
	
	/**
	 * Configura a variáver $url
	 * @param string $url
	 */	
	public function setUrl(string $url) {
		$this->url = $url;
	}
	
	/**
	 * Faz o redirecionameto da página
	 * @return void
	 */
	public function reload() {
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='0;URL={$this->url}'>";
	}
}