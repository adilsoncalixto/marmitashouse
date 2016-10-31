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
		
		switch ($class)
		{
			case 'ControlLogin':
				return (new ControlLogin());
				break;
			case 'ControlHome':
				return (new ControlHome());
				break;
			case 'ControlCliente':
				return (new ControlCliente());
				break;
			case 'ControlProduto':
				return (new ControlProduto());
				break;
			case 'ControlPedido':
				return (new ControlPedido());
				break;
			case 'ControlEntregador':
				return (new ControlEntregador());
				break;
			case 'ControlEmpresaTerceirizada':
				return (new ControlEmpresaTerceirizada());
				break;
			case 'ControlCaixa':
				return (new ControlCaixa());
				break;
			case 'ControlUsuario':
				return (new ControlUsuario());
				break;
			default:
				throw new Exception('Classe de controle não encontrada!');
				break;
		}
	}
}