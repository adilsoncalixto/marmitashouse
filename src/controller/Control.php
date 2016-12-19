<?php

namespace App\controller;

use Exception;

/**
 * Controla qual classe será instanciada e retornada
 * @author Jorge Lucas
 */
class Control
{
	/**
	 * Recebe uma string com o nome da classe controladora, case a encontre, retorna-a instanciada
	 * @param string $class Noma da classe
	 * @return object Instância de uma classe
	 */
	public static function load(string $class) {
		
		/**
		 * Verifica a classe chamada e, então, retorna sua
		 * instância. Senão, lança uma exceção.
		 */
        	$class = "App\controller\\$class";
		if(class_exists($class)) {
		    return (new $class());   
		} else {
		    throw new Exception('Classe de controle não encontrada!');
		}
	}
}
