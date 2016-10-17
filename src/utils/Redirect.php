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
	 * Faz o redirecionameto da página dentro do tempo determinado
	 * @param int $time Tempo (em segundos) en que a página recarregará
	 * @return void
	 */
	public function reload(int $time = 0) {
		echo "<meta HTTP-EQUIV='Refresh' CONTENT='{$time};URL={$this->url}'>";
	}
}